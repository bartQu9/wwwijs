//ustawiam plugin Drag&Drop aby refreshowal strone po zaladowaniu obrazu
Dropzone.options.draganddrop = {
    maxFilesize: 10,
    maxFiles: 1,
    init: function () {
        // Set up any event handlers
        this.on('queuecomplete', function () {
            location.reload();
        });
    }
};

//utrudniam pobieranie zdjec poprzez wylaczenie ppm na img
$('img').mousedown(function (e) {
    if(e.button == 2) { // right click
        return false; // do nothing!
    }
});

$.ready(function(){
    let entries = $("#img_categories_div").find(".dropdown-menu");
    let resp = $.post( "manage.php", { action: "get_available_categories"}, function (resp) {return resp} );
    let categories = JSON.parse(resp.responseText);
    for(cat in categories){
        let a = document.createElement("a");
        let text = document.createTextNode(cat["key"]);
        a.appendChild(text);
        a.href="#";
        a.title = cat["key"];
        tag.appendChild(text);
        entries.appendChild(a);
    }
})

function delete_photo(photo_db_id) {
    $.post( "manage.php", { action: "delete", id: photo_db_id }, function () {location.reload();} );
}
function rename(photo_db_id, newname) {
    $.post( "manage.php", { action: "rename", id: photo_db_id, newname: newname }, function () {location.reload();} );
}
function change_desc(photo_db_id, newdesc) {
    $.post( "manage.php", { action: "change_desc", id: photo_db_id, newdesc: newdesc }, function () {location.reload();} );
}
function add_category(category_name) {
    $.post( "manage.php", { action: "add_category", category_name: category_name }, function () {location.reload();} );
}
function del_category(category_id) {
    $.post( "manage.php", { action: "del_category", category_id: category_id }, function () {location.reload();} );
}
function photo_to_category(photo_id, category_id) {
    $.post( "manage.php", { action: "add_photo_to_category", category_id: category_id, photo_id: photo_id },
        function () {location.reload();});
}
function del_photo_from_category(photo_id, category_id) {
    $.post( "manage.php", { action: "del_photo_from_category", category_id: category_id, photo_id: photo_id },
        function () {location.reload();});
}
function on_click_rename(photo_db_id){
    $("#rename_modal").modal(show=true);


    $("#rename_modal").find("#rename_save_btn").click(function(){
        let newtitle = $("#rename_modal").find("#newtitle_input").val();
        rename(photo_db_id, newtitle)
    });
}

function on_click_change_desc(photo_db_id){
    $("#change_desc_modal").modal(show=true);

    $("#change_desc_modal").find("#change_desc_save_btn").click(function(){
        let newdesc = $("#change_desc_modal").find("#new_desc_input").val();
        change_desc(photo_db_id, newdesc)
    });
}

function on_click_categories(photo_db_id){
    let resp = $.post( "manage.php", { action: "get_available_categories"}, function (resp) {return resp} );
    let categories = JSON.parse(resp.responseText);
    let categories_modal = $("#change_img_categories_modal").modal(show=true);
    categories_modal.find(".modal-body");
}