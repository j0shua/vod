var fg = null;


function insert_image(image_file_path) {
    parent.insert_image(input_form_id, image_file_path);
}
$(function() {

    fg = $("#main-table").flexigrid({
        url: ajax_grid_url,
        dataType: 'json',
        colModel: [{
                display: 'เลขที่สื่อ',
                name: 'resource_id',
                width: 45,
                sortable: true,
                align: 'right'
            }, {
                display: 'แทรกภาพ',
                name: 'action_insert',
                width: 70,
                align: 'left'
            }, {
                display: 'ตัวอย่าง',
                name: 'thumbnail',
                width: 100,
                align: 'left'

            }, {
                display: 'ชื่อสื่อ',
                name: 'title_play',
                width: 180,
                sortable: true,
                align: 'left'

            }, {
                display: 'ขนาดไฟล์',
                name: 'h_file_size',
                width: 50,
                align: 'left'
            }, {
                display: 'รูปแแบบไฟล์',
                name: 'file_ext',
                width: 55,
                align: 'left'
            }, {
                display: 'กว้าง',
                name: 'width',
                width: 55,
                align: 'left'
            }, {
                display: 'สูง',
                name: 'height',
                width: 55,
                align: 'left'
            }, {
                display: 'การนำไปใช้',
                name: 'publish',
                width: 40,
                align: 'left'

            }, {
                display: 'สิทธิการใช้',
                name: 'privacy',
                width: 40,
                align: 'left'

            }, {
                display: 'กระทำการ',
                name: 'action',
                width: 300,
                align: 'left'

            }
        ],
        sortname: "resource_id",
        sortorder: "desc",
        usepager: true,
        title: '',
        useRp: true,
        rp: 20,
        showTableToggleBtn: true,
        width: '100%',
        height: 450,
        singleSelect: true
    });

    $("#btn_search").click(function() {
        var query = $("#qtype").val() + '=' + $("#query").val();
        fg.flexOptions({
            query: query,
            qtype: 'custom'
        }).flexReload();

    });
});