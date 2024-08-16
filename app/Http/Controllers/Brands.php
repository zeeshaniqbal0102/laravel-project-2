<?php

namespace App\Http\Controllers;

use App\Brand as Brand;
use Illuminate\Support\Facades\Input;
use Validator, Hash;
use Illuminate\Http\Request;

class Brands extends MainController {

    public function index() {
        return view('brands.list');
    }

    public function data_list() {
        $start = (int)Input::get("start");
        $length = ((int)Input::get("length") > 0 ? (int)Input::get("length") : 25);

        $columns = ["brand.brand_id", "brand.brand_id", "chain.name", "brand.name", "brand.status", "brand.added_by", "brand.modified_by"];

        $query = Brand::selectRaw("brand.brand_id,brand.name, a_updated.Username as ModifiedBy,brand.status, a_added.Username as AddedBy, chains.name as chain_name, brand.added_by, brand.created_at, brand.modified_by, brand.updated_at")
            ->leftJoin('users as a_added', 'brand.added_by', '=', 'a_added.UserID')
            ->leftJoin('chain as chains', 'brand.chain_id', '=', 'chains.chain_id')
            ->leftJoin('users as a_updated', 'brand.modified_by', '=', 'a_updated.UserID');

        $recordsTotal = count($query->get());

        if (Input::get("search")["value"] != "") {
            $input = escape(trim(Input::get("search")["value"]));
            $query->whereRaw("(chains.name LIKE '%" . $input . "%' OR brand.name LIKE '%" . $input . "%')");
        }

        $query->orderBy($columns[(int)Input::get("order")[0]["column"]], Input::get("order")[0]["dir"]);

        $recordsFiltered = count($query->get());
        $result = $query->skip($start)->take($length)->get();
        $data = [];
        foreach ($result as $Rs) {
//          dd($Rs->DateAdded);
            $data[] = [
                '<div class="chk-list md-checkbox has-success"><input type="checkbox" id="checkbox' . $Rs->brand_id . '" class="md-check checkboxes" name="ids[]" value="' . $Rs->brand_id . '"><label for="checkbox' . $Rs->brand_id . '"><span class="inc"></span><span class="check"></span><span class="box"></span> </label></div>',
                $Rs->brand_id,
                unescape($Rs->chain_name),
                unescape($Rs->name),
                '<button type="button" class="btn btn-' . ($Rs->status == 0 ? 'success' : 'danger') . ' btn-xs btn-status" id="' . $Rs->brand_id . '" Status="' . $Rs->status . '" data-toggle="tooltip" ' . ($Rs->status == 0 ? 'title="Click to activate"' : 'title="Click to deactivate"') . '' . ($Rs->IsSuperAdmin == 1 ? ' disabled="disabled"' : '') . '><i class="fa ' . ($Rs->status == 1 ? 'fa-eye-slash' : 'fa-eye') . '"></i> ' . ($Rs->status == 0 ? "Active" : "Deactive") . '</button>',
                '<b>' . $Rs->AddedBy . '</b><br />' . $Rs->created_at,
                '<b>' . (!empty($Rs->ModifiedBy) ? $Rs->ModifiedBy : '--') . '</b><br />' . $Rs->updated_at,
                '<button type="button" onclick="location.href=\'' . url('brands/edit') . '/' . $Rs->brand_id . '\'" class="btn btn-success btn-xs btnEdit"' . ($Rs->IsSuperAdmin == 1 ? ' disabled="disabled"' : '') . '><i class="fa fa-edit"></i> Edit</button>'
            ];
        }

        echo json_encode(["draw" => (int)Input::get("draw"), "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, "data" => $data]);
        exit(0);
    }

    public function add() {
        $this->check_access('Brands', 'Add');
        $this->data["Chains"] = ["" => "-- Select Hotel Chain --"] + \App\Chain::select("chain_id", "name")
                        ->where("status", 1)->pluck("name", "chain_id")->toArray();

        return view('brands.add', $this->data);
    }

    public function save() {
        $this->check_access('Brands', 'Add');
        $v = Validator::make(Input::all(), [
            'chain_id' => 'required|min:1',
            'name' => 'required|max:100',
            "chain_id.min" => "Please select chain.",
            "status.min" => "Please select Status.",
            "status.max" => "Please select Status.",
        ]);

        $v->setAttributeNames([
            'chain_id' => "Hotel Chain",
            'name' => "Hotel Brand Name",
            'status' => "status",
            //'ProfilePicture' => "Profile Picture",
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        } else {
            $Record = new Brand();
            $Record->chain_id = (int) Input::get('chain_id');
            $Record->name = escape(Input::get('name'));
            $Record->status = (int) Input::get('status');
            $Record->added_by = \Session::get('UserID');
            $Record->save();
            return redirect('brands/add')->with('success', "Hotel Brand has been added successfully.");
        }
    }

    public function get_brands($id) {

        $get_brands = \App\Brand::select("brand_id", "name")->where("chain_id", $id)->get()->toArray();
        echo json_encode($get_brands);
    }
    
    public function edit($id) {
        $this->check_access('Brands', 'Edit');
        $this->data["Chains"] = ["" => "-- Select Hotel Chain --"] + \App\Chain::select("chain_id", "name")
        ->where("status", 1)->pluck("name", "chain_id")->toArray();

        $this->data["Record"] = Brand::find($id);
        if (!empty($this->data["Record"])) {
            return view('brands.edit', $this->data);
        } else {
            return redirect("brands")->withErrors("Invalid Brand ID.");
        }
    }

    public function update($id) {
        $this->check_access('Brands', 'Edit');
        $v = Validator::make(Input::all(), [
            'chain_id' => 'required|min:1',
            'name' => 'required|max:100',

            "chain_id.min" => "Please select chain.",
            "status.min" => "Please select Status.",
            "status.max" => "Please select Status.",
        ]);

        $v->setAttributeNames([
            'chain_id' => "Hotel Chain",
            'name' => "Hotel Brand Name",
            'status' => "status",
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        } else {
            $Record = Brand::find($id);
            if (!empty($Record)) {
                $Record->chain_id = (int) Input::get('chain_id');
                $Record->name = escape(Input::get('name'));
                $Record->status = (int) Input::get('status');
                $Record->modified_by = \Session::get('UserID');
                $Record->save();
                return redirect('brands/edit/' . $id)->with('success', "Brand has been updated successfully.");
            } else {
                return redirect("brands")->withErrors("Invalid Brand ID.");
            }
        }
    }

    public function update_status() {

        $this->check_access('Brands', 'Edit', true);
        $v = Validator::make(Input::all(), [
                    'sid' => 'required|integer',
                    'status' => 'required|integer|min:0|max:1',
        ]);
        if ($v->fails()) {
            echo json_encode(['updated' => false]);
        } else {
            Brand::where("brand_id", (int) Input::get('sid'))->update([
                "status" => (int) Input::get('status')
            ]);
            echo json_encode(['updated' => true, 'status' => Input::get('status')]);
        }
    }

    public function delete() {
        $this->check_access('Brands', 'Delete');
        try {
            if (is_array(Input::get('ids')) && count(Input::get('ids')) > 0) {
                Brand::whereIn('brand_id', Input::get('ids'))->delete();
                return redirect('brands')->with('success', "Selected records have been deleted successfully.");
            } else {
                return redirect('brands')->with('warning_msg', "Please select records to delete.");
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->with("warning_msg", "Some records can not be deleted.");
        }
    }

}
