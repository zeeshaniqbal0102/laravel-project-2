<?php

namespace App\Http\Controllers;

use App\Agreement;
use App\AgreeAmen;
use App\RoomType;
use Illuminate\Support\Facades\Input;
use Validator,
    Hash;
use Illuminate\Http\Request;

class Agreements extends MainController
{

    public function index()
    {
        return view('agreements.list',$this->data);
    }

    public function data_list()
    {
        if (!$this->check_access('Hotel Agreements', 'List', true)) {
            echo json_encode(["draw" => (int)Input::get("draw"), "recordsTotal" => 0, "recordsFiltered" => 0, "data" => []]);
            exit(0);
        } else {
            $start = (int)Input::get("start");
            $length = ((int)Input::get("length") > 0 ? (int)Input::get("length") : 25);

            $columns = ["agreement.agreement_id", "agreement.agreement_id", "agreement.hotel_name", "ChainBrand", "agreement.city", "agreement.commission", "agreement.date_to", "agreement.status", "agreement.added_by", "agreement.modified_by"];

            $query = Agreement::selectRaw("agreement.agreement_id, agreement.hotel_name, agreement.city, chains.name, brands.name, agreement.date_to,concat(chains.name, '/', brands.name) as ChainBrand, agreement.commission, commission.comm_type, a_updated.Username as ModifiedBy,agreement.status, a_added.Username as AddedBy, agreement.added_by, agreement.created_at, agreement.modified_by, agreement.updated_at")
                ->leftJoin('users as a_added', 'agreement.added_by', '=', 'a_added.UserID')
                ->leftJoin('chain as chains', 'agreement.chain_id', '=', 'chains.chain_id')
                ->leftJoin('brand as brands', 'agreement.brand_id', '=', 'brands.brand_id')
                ->leftJoin('commission', 'agreement.comm_id', '=', 'commission.comm_id')
                ->leftJoin('users as a_updated', 'agreement.modified_by', '=', 'a_updated.UserID');

            $recordsTotal = $query->count();

            if (Input::get("search")["value"] != "") {
                $input = escape(trim(Input::get("search")["value"]));
                $query->whereRaw("(chains.name LIKE '%" . $input . "%' OR brands.name LIKE '%" . $input . "%' OR agreement.hotel_name LIKE '%".$input."%')");
            }


            $query->orderBy($columns[(int)Input::get("order")[0]["column"]], Input::get("order")[0]["dir"]);

            $recordsFiltered = count($query->get());
            $result = $query->skip($start)->take($length)->get();
            $data = [];
            foreach ($result as $Rs) {
                //dd($Rs->DateAdded);
                $data[] = [
                    '<div class="chk-list md-checkbox has-success"><input type="checkbox" id="checkbox' . $Rs->agreement_id . '" class="md-check checkboxes" name="ids[]" value="' . $Rs->agreement_id . '"><label for="checkbox' . $Rs->agreement_id . '"><span class="inc"></span><span class="check"></span><span class="box"></span> </label></div>',
                    $Rs->agreement_id,
                    unescape($Rs->hotel_name),
                    unescape($Rs->ChainBrand),
                    unescape($Rs->city),
                    number_format($Rs->commission, 2).'% ('.unescape($Rs->comm_type).')',
                    $Rs->date_to,
                    '<button type="button" class="btn btn-' . ($Rs->status == 0 ? 'success' : 'danger') . ' btn-xs btn-status" id="' . $Rs->agreement_id . '" status="' . $Rs->status . '" data-toggle="tooltip" ' . ($Rs->status == 0 ? 'title="Click to activate"' : 'title="Click to deactivate"') . '' . ($Rs->IsSuperAdmin == 1 ? ' disabled="disabled"' : '') . '><i class="fa ' . ($Rs->status == 1 ? 'fa-eye-slash' : 'fa-eye') . '"></i> ' . ($Rs->status == 0 ? "Active" : "Deactive") . '</button>',
                    '<b>' . $Rs->AddedBy . '</b><br />' . $Rs->created_at,
                    '<b>' . (!empty($Rs->ModifiedBy) ? $Rs->ModifiedBy : '--') . '</b><br />' . $Rs->updated_at,
                    '<button type="button" onclick="location.href=\'' . url('agreements/edit') . '/' . $Rs->agreement_id . '\'" class="btn btn-success btn-xs btnEdit"' . ($Rs->IsSuperAdmin == 1 ? ' disabled="disabled"' : '') . '><i class="fa fa-edit"></i> Edit</button>'
                ];
            }

            echo json_encode(["draw" => (int)Input::get("draw"), "recordsTotal" => $recordsTotal, "recordsFiltered" => $recordsFiltered, "data" => $data]);
            exit(0);
        }
    }

    public function GetDropDownList()
    {
        $this->data["Chains"] = ["" => "-- Select Hotel Chain --"] + \App\Chain::select("chain_id", "name")->where("status", 1)->pluck("name", "chain_id")->toArray();
        $this->data["States"] = ["" => "-- Select State --"] + \App\State::selectRaw("state_id, CONCAT(name, ' (', code, ')') as state_name")->where("status", 1)->pluck("state_name", "state_id")->toArray();
        $this->data["BillingTypes"] = ["" => "-- Select Billing Type --"] + \App\Billing::select("billing_id", "type")->pluck("type", "billing_id")->toArray();
        $this->data["Companies"] = ["" => "-- Select Company --"] + \App\Company::select("company_id", "company_name")->pluck("company_name", "company_id")->toArray();
        $this->data["Rep"] = ["" => "-- Select Representative --"] + \App\Users::where("IsSuperAdmin", 0)->select("UserID", "Username")->pluck("Username", "UserID")->toArray();
        $this->data["Amenities"] = \App\Amenity::select("amen_id", "amen_desc")->where("status", 1)->pluck("amen_desc", "amen_id")->toArray();
        $this->data["CommissionTypes"] = ["" => "-- Select Commission Type --"]+\App\Commission::pluck("comm_type", "comm_id")->toArray();
    }

    public function add()
    {
        $this->check_access('Hotel Agreements', 'Add');
        $this->GetDropDownList();
        return view('agreements.add', $this->data);
    }

    public function save()
    {
        $this->check_access('Hotel Agreements', 'Add');

        $rules['chain_id'] = 'required|integer|min:1';
        $rules['brand_id'] = 'required|integer|min:1';
        $rules['hotel_name'] = 'required|max:100';
        $rules['contact_name'] = 'required|max:100';
        $rules['email_address'] = 'email|required|max:255|unique:agreement';
        $rules['phone'] = 'required|max:20';
        $rules['address_1'] = 'required|max:100';
        $rules['state_id'] = 'required|integer|min:1';
        $rules['city'] = 'required|max:30';
        $rules['zip'] = 'integer|required';
        $rules['latitude'] = 'required|numeric';
        $rules['longitude'] = 'required|numeric';
        $rules['date_from'] = 'required|date_format:Y-m-d|before:date_to';
        $rules['date_to'] = 'required|date_format:Y-m-d|after:date_from';
        if(\Session::get("IsSuperAdmin")) {
            $rules['UserID'] = 'required|min:1';
        }
        $rules['billing_id'] = 'required|integer|min:1';
        $rules['AgreementFor'] = 'required|integer|min:1|max:2';
        if((int)Input::get("AgreementFor") == 2) {
            $rules['company_id'] = 'required|integer|min:1';
        }
        $rules['rate_type'] = 'required|integer|min:1|max:2';
        $rules['comm_id'] = 'required|integer';
        $rules['commission'] = 'required|numeric';

        $v = Validator::make(Input::all(), $rules, [
            "chain_id.min" => "Please select Hotel Chain.",
            "brand_id.min" => "Please select Hotel Brand.",
            "state_id.min" => "Please select State.",
            "UserID.min" => "Please select Representative.",
            "billing_id.min" => "Please select Billing.",
            "AgreementFor.min" => "Please specify whether agreement for all or specific client.",
            "AgreementFor.max" => "Please specify whether agreement for all or specific client.",
            "company_id.min" => "Please select Company.",
            "rate_type.min" => "Please select Rate Type.",
            "rate_type.max" => "Please select Rate Type.",
            "comm_id.integer" => "Please select Commission Type.",
        ]);

        $v->setAttributeNames([
            'chain_id' => "Hotel Chain",
            'brand_id' => "Hotel Brand",
            'hotel_name' => "Hotel Name",
            'contact_name' => "Contact Name",
            'email_address' => "Email Address",
            'phone' => "Phone #",
            'address_1' => "Address 1",
            'state_id' => 'State',
            'city' => 'City',
            'zip' => 'Zip',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'date_from' => 'Begin Date',
            'date_to' => 'End Date',
            'billing_id' => 'Billing',
            'AgreementFor' => 'Agreement For',
            'UserID' => 'Representative',
            'rate_type' => 'Rate Type',
            'comm_id' => 'Commission Type',
            'commission' => 'Commission',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        } else {
            if((int)Input::get("TotalRooms") == 0) {
                return redirect()->back()->withErrors("Please add at least one Room Type.")->withInput();
            } else {
                $msgs = '';
                for($i = 1; $i <= (int)Input::get("TotalRooms"); $i++) {
                    if(!in_array((int)Input::get("RoomType".$i), array_keys($this->data["_RoomTypes"]))) {
                        $msgs .= "Room Type ".$i." must be in ".array_values($this->data["_RoomTypes"]).".\n";
                    }
                    if(!is_numeric(Input::get("RoomRate".$i))) {
                        $msgs .= "Room Rate ".$i." must be numeric.\n";
                    }
                }

                if($msgs != "") {
                    return redirect()->back()->withErrors($msgs)->withInput();
                } else {
                    $Record = new Agreement();
                    $Record->chain_id = (int)Input::get('chain_id');
                    $Record->brand_id = (int)Input::get('brand_id');
                    $Record->hotel_name = Input::get('hotel_name');
                    $Record->contact_name = Input::get('contact_name');
                    $Record->email_address = Input::get('email_address');
                    $Record->phone = escape(Input::get('phone'));
                    $Record->fax = Input::get('fax');
                    $Record->address_1 = escape(Input::get('address_1'));
                    $Record->address_2 = escape(Input::get('address_2'));
                    $Record->city = escape(Input::get('city'));
                    $Record->state_id = (int)(Input::get('state_id'));
                    $Record->zip = (int)Input::get('zip');
                    $Record->latitude = (double)Input::get('latitude');
                    $Record->longitude = (double)Input::get('longitude');
                    $Record->date_from = Input::get('date_from');
                    $Record->date_to = Input::get('date_to');
                    $Record->UserID = (\Session::get("IsSuperAdmin") ? (int)Input::get('UserID') : \Session::get('UserID'));
                    $Record->billing_id = (int)Input::get('billing_id');
                    $Record->agreement_for = (int)Input::get('agreement_for');
                    $Record->rate_type = (int)Input::get('rate_type');
                    $Record->rate_details = escape(Input::get('rate_details'));
                    $Record->comm_id = (int)Input::get('comm_id');
                    $Record->commission = (float)Input::get('commission');
                    $Record->added_by = \Session::get('UserID');
                    $Record->status = 1;
                    $Record->save();

                    $agreement_id = $Record->agreement_id;
                    $guid = (string)\Uuid::generate().'-'.$agreement_id;
                    $Record->guid = $guid;
                    $Record->save();

                    if (is_array(Input::get("amen_id")) && count(Input::get("amen_id")) > 0) {
                        foreach (Input::get("amen_id") as $amenity) {
                            $Agree_Amen = new AgreeAmen();
                            $Agree_Amen->amen_id = $amenity;
                            $Agree_Amen->agreement_id = $agreement_id;
                            $Agree_Amen->save();
                        }
                    }

                    for($i = 1; $i <= (int)Input::get("TotalRooms"); $i++) {
                        $RoomType = new RoomType();
                        $RoomType->agreement_id = $agreement_id;
                        $RoomType->room_type = Input::get("RoomType".$i);
                        $RoomType->room_rate = (float)Input::get("RoomRate".$i);
                        $RoomType->save();
                    }

                    $email_contents = view("includes.emails.agreement", [
                        "ContactName" => Input::get('contact_name'),
                        "AgreementLink" => url("agreement/sign/".$guid)
                    ])->render();

                    $sent = $this->SendEmail(Input::get('email_address'), Input::get('contact_name'), \Session::get("EmailAddress"), \Session::get("FullName"), "Lodging Source - Hotel Agreement", $email_contents);

                    if(!$sent) {
                        return redirect("agreements/add")->with("warning_msg", "Hotel Agreement has been added but email could not be sent.");
                    }

                    return redirect('agreements/add')->with('success', "Hotel Agreement has been added successfully.");
                }
            }
        }
    }

    public function edit($id)
    {
        $this->check_access('Hotel Agreements', 'Edit');
        $this->GetDropDownList();
        $this->data["Record"] = Agreement::find($id);
        $this->data["RoomTypesIDs"] = [];
        $this->data["SignStatus"] = ["Not Singed", "Singed"];
        if (!empty($this->data["Record"])) {
            $RoomTypes = \App\RoomType::where("agreement_id", $id)->get();
            $this->data["RoomsTotal"] = count($RoomTypes);

            $i = 1;
            $this->data["RoomTypes"] = [];
            foreach($RoomTypes as $RoomType) {
                $this->data["RoomTypes"][$i] = ["RoomType" => $RoomType->room_type, "RoomRate" => $RoomType->room_rate];
                $i++;
            }

            $this->data["AgreementAmenities"] = AgreeAmen::select("amen_id")->where("agreement_id", $id)->get()->pluck("amen_id")->toArray();

            return view('agreements.edit', $this->data);
        } else {
            return redirect("agreements")->withErrors("Invalid Hotel Agreement ID.");
        }
    }

    public function update($id) {

        $Record = Agreement::findOrFail($id);

        $this->check_access('Hotel Agreements', 'Edit');
        $rules['chain_id'] = 'required|integer|min:1';
        $rules['brand_id'] = 'required|integer|min:1';
        $rules['hotel_name'] = 'required|max:100';
        $rules['contact_name'] = 'required|max:100';
        $rules['email_address'] = 'email|required|max:255|unique:agreement,email_address,'.$id.',agreement_id';
        $rules['phone'] = 'required|max:20';
        $rules['address_1'] = 'required|max:100';
        $rules['state_id'] = 'required|integer|min:1';
        $rules['city'] = 'required|max:30';
        $rules['zip'] = 'integer|required';
        $rules['latitude'] = 'required|numeric';
        $rules['longitude'] = 'required|numeric';
        $rules['date_from'] = 'required|date_format:Y-m-d|before:date_to';
        $rules['date_to'] = 'required|date_format:Y-m-d|after:date_from';
        if(\Session::get("IsSuperAdmin")) {
            $rules['UserID'] = 'required|min:1';
        }
        $rules['billing_id'] = 'required|integer|min:1';
        $rules['AgreementFor'] = 'required|integer|min:1|max:2';
        if((int)Input::get("AgreementFor") == 2) {
            $rules['company_id'] = 'required|integer|min:1';
        }
        $rules['rate_type'] = 'required|integer|min:1|max:2';
        $rules['comm_id'] = 'required|integer';
        $rules['commission'] = 'required|numeric';

        $v = Validator::make(Input::all(), $rules, [
            "chain_id.min" => "Please select Hotel Chain.",
            "brand_id.min" => "Please select Hotel Brand.",
            "state_id.min" => "Please select State.",
            "UserID.min" => "Please select Representative.",
            "billing_id.min" => "Please select Billing.",
            "AgreementFor.min" => "Please specify whether agreement for all or specific client.",
            "AgreementFor.max" => "Please specify whether agreement for all or specific client.",
            "company_id.min" => "Please select Company.",
            "rate_type.min" => "Please select Rate Type.",
            "rate_type.max" => "Please select Rate Type.",
            "comm_id.integer" => "Please select Commission Type.",
        ]);

        $v->setAttributeNames([
            'chain_id' => "Hotel Chain",
            'brand_id' => "Hotel Brand",
            'hotel_name' => "Hotel Name",
            'contact_name' => "Contact Name",
            'email_address' => "Email Address",
            'phone' => "Phone #",
            'address_1' => "Address 1",
            'state_id' => 'State',
            'city' => 'City',
            'zip' => 'Zip',
            'latitude' => 'Latitude',
            'longitude' => 'Longitude',
            'date_from' => 'Begin Date',
            'date_to' => 'End Date',
            'billing_id' => 'Billing',
            'AgreementFor' => 'Agreement For',
            'UserID' => 'Representative',
            'rate_type' => 'Rate Type',
            'comm_id' => 'Commission Type',
            'commission' => 'Commission',
        ]);
        if ($v->fails()) {
            return redirect()->back()->withErrors($v->errors())->withInput();
        } else {
            if((int)Input::get("TotalRooms") == 0) {
                return redirect()->back()->withErrors("Please add at least one Room Type.")->withInput();
            } else {
                $msgs = '';
                for ($i = 1; $i <= (int)Input::get("TotalRooms"); $i++) {
                    if (!in_array((int)Input::get("RoomType" . $i), array_keys($this->data["_RoomTypes"]))) {
                        $msgs .= "Room Type " . $i . " must be in " . array_values($this->data["_RoomTypes"]) . ".\n";
                    }
                    if (!is_numeric(Input::get("RoomRate" . $i))) {
                        $msgs .= "Room Rate " . $i . " must be numeric.\n";
                    }
                }

                if ($msgs != "") {
                    return redirect()->back()->withErrors($msgs)->withInput();
                } else {
                    $Record->chain_id = (int)Input::get('chain_id');
                    $Record->brand_id = (int)Input::get('brand_id');
                    $Record->hotel_name = Input::get('hotel_name');
                    $Record->contact_name = Input::get('contact_name');
                    $Record->email_address = Input::get('email_address');
                    $Record->phone = escape(Input::get('phone'));
                    $Record->fax = Input::get('fax');
                    $Record->address_1 = escape(Input::get('address_1'));
                    $Record->address_2 = escape(Input::get('address_2'));
                    $Record->city = escape(Input::get('city'));
                    $Record->state_id = (int)(Input::get('state_id'));
                    $Record->zip = (int)Input::get('zip');
                    $Record->latitude = (double)Input::get('latitude');
                    $Record->longitude = (double)Input::get('longitude');
                    $Record->date_from = Input::get('date_from');
                    $Record->date_to = Input::get('date_to');
                    $Record->UserID = (\Session::get("IsSuperAdmin") ? (int)Input::get('UserID') : \Session::get('UserID'));
                    $Record->billing_id = (int)Input::get('billing_id');
                    $Record->agreement_for = (int)Input::get('agreement_for');
                    $Record->rate_type = (int)Input::get('rate_type');
                    $Record->rate_details = escape(Input::get('rate_details'));
                    $Record->comm_id = (int)Input::get('comm_id');
                    $Record->commission = (float)Input::get('commission');
                    $Record->modified_by = \Session::get('UserID');
                    $Record->save();

                    AgreeAmen::where("agreement_id", $id)->delete();
                    if (is_array(Input::get("amen_id")) && count(Input::get("amen_id")) > 0) {
                        foreach (Input::get("amen_id") as $amenity) {
                            $Agree_Amen = new AgreeAmen();
                            $Agree_Amen->amen_id = $amenity;
                            $Agree_Amen->agreement_id = $id;
                            $Agree_Amen->save();
                        }
                    }

                    RoomType::where("agreement_id", $id)->delete();
                    for($i = 1; $i <= (int)Input::get("TotalRooms"); $i++) {
                        $RoomType = new RoomType();
                        $RoomType->agreement_id = $id;
                        $RoomType->room_type = Input::get("RoomType".$i);
                        $RoomType->room_rate = (float)Input::get("RoomRate".$i);
                        $RoomType->save();
                    }

                    $sent = true;
                    if ((int)Input::get("SendAgreement") == 1) {
                        $guid = (string)\Uuid::generate() . '-' . $id;
                        $Record->guid = $guid;
                        $Record->save();

                        $email_contents = view("includes.emails.agreement", [
                            "ContactName" => Input::get('contact_name'),
                            "AgreementLink" => url("agreement/sign/" . $Record->guid)
                        ])->render();

                        $sent = $this->SendEmail(Input::get('email_address'), Input::get('contact_name'), \Session::get("EmailAddress"), \Session::get("FullName"), "Lodging Source - Hotel Agreement", $email_contents);
                    }

                    if (!$sent) {
                        return redirect('agreements/edit/' . $id)->with("warning_msg", "Hotel Agreement has been updated but email could not be sent.");
                    }

                    return redirect('agreements/edit/' . $id)->with('success', "Agreement has been updated successfully.");
                }
            }
        }
    }

    public function update_status()
    {

        $this->check_access('Hotel Amenities', 'Edit', true);

        $v = Validator::make(Input::all(), [
            'sid' => 'required|integer',
            'status' => 'required|integer|min:0|max:1',
        ]);
        if ($v->fails()) {
            echo json_encode(['updated' => false]);
        } else {
            Agreement::where("agreement_id", (int)Input::get('sid'))->update([
                "status" => (int)Input::get('status')
            ]);
            echo json_encode(['updated' => true, 'status' => Input::get('status')]);
        }
    }

    public function delete()
    {
        $this->check_access('Hotel Agreements', 'Delete');
        try {
            if (is_array(Input::get('ids')) && count(Input::get('ids')) > 0) {
                Brand::whereIn('agreement_id', Input::get('ids'))->delete();
                return redirect('agreements')->with('success', "Selected records have been deleted successfully.");
            } else {
                return redirect('agreements')->with('warning_msg', "Please select records to delete.");
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            return redirect()->back()->with("warning_msg", "Some records can not be deleted.");
        }
    }
}
