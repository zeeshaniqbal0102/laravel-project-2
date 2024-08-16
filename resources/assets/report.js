
$("#filter").click(function () {

    table.fnReloadAjax(Globals.url + Globals.urlList + '?job_no=' + $("#job_id").val() + '&rep=' + $("#UserID").val()+ '&title=' + $("#title").val()
        + '&guest=' + $("#guest").val() + '&chain_id=' + $("#chain_id").val() + '&brand_id=' + $("#brand_id").val() + '&city=' + $("#city").val()
        + '&state=' + $("#state_id").val() + '&date_from=' + $("#date_from").val() + '&date_to=' + $("#date_to").val());
    table.fnClearTable(0);
    table.fnDraw();
});

$(document).on('click', ".removebutton", function () {
    let t = $(this).data("values");
    t = JSON.parse(JSON.parse(t));
    $("#job_id").val(t.job_id);
    $("#chain_id").val(t.chain_id).trigger("change");
    $("#brand_id").val(t.brand_id).trigger("change");
    $("#UserID").val(t.UserID).trigger("change");
    $("#state_id").val(t.state_id).trigger("change");
    $("#pay_id").val(t.pay_id).trigger("change");
    $("#title").val(t.title);
    $("#guest").val(t.guest);
    $("#city").val(t.city);
    $("#date_from").val(t.date_from);
    $("#date_to").val(t.date_to);
    $("#conf_no").val(t.conf_no);
    $("#ls_rate").val(t.ls_rate);
    $("#hotel_rate").val(t.hotel_rate);
    $("#ttl_nights").val(t.ttl_nights);
    $("#hotel_name").val(t.hotel_name);
    $("#mng_company").val(t.mng_company);
    $("#hotel_search").val(t.hotel_search);
    $("#coding").val(t.coding);
    $("#ttl_room_cost").val(t.ttl_room_cost);
    $("#ttl_tax").val(t.ttl_tax);
    $("#misc").val(t.misc);
    $("#savings").val(t.savings);
    $("#ttl_cost").val(t.ttl_cost);
    $("#filter").triggerHandler("click");
});

$(".frm_submit").click(function () {
    $.ajax({
        type: "post",
        url: Globals.url + "{{/reports-filter}}",
        data: $("#save_form").serialize(),
        dataType: "json",
        success: function () {
        }
    });
});
$("#reset_dash").click(function () {
    table.fnReloadAjax(Globals.url + Globals.urlList);
    table.fnClearTable(0);
    table.fnDraw();
});
$(".select2").select2();
