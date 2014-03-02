

<form id="">
    <div class="grid_12">
        <h2>Add Playlist</h2>
        <div id="list-header">
            <label for="title">Playlist title</label>
            <input type="text" name="title"/>
        </div>
    </div>
    <div id="list-wraper" class="grid_8">
        <input type="button" value="Add" onclick="add_list();"/>
        <input type="button" value="Add" onclick="del_list();"/>

        <ul id="sortable">
            <li class="ui-state-default"><div class="list-cursor"><input type="checkbox" name="" value="ON" /></div><div class="video-detail">Itemfdsfdsfdsf  fdsf 1<input type="hidden" name="a" value="1" /></div></li>
            <li class="ui-state-default"><div class="list-cursor"><input type="checkbox" name="" value="ON" /></div><div class="video-detail">Item 2</div><input type="hidden" name="b" value="2" /></li>
            <li class="ui-state-default"><div class="list-cursor"><input type="checkbox" name="" value="ON" /></div><div class="video-detail">Item 3</div><input type="hidden" name="c" value="3" /></li>
        </ul>
    </div>
    <div id="list-detail" class="grid_4">
        <label for="description">Description</label>
        <textarea name="description" style="width: 100%;"></textarea>

    </div>
</form>

<script>
    $(function() {
        $( "#sortable" ).sortable(
        { cancel:'.video-detail'}    
    );
        $( "#sortable" ).disableSelection();
    });
    function add_list(){
        $('<li class="ui-state-default"><div class="list-cursor"><input type="checkbox" name="" value="ON" /></div><div class="video-detail">Item 7</div></li>').appendTo('#sortable');
    }
    function del_list(){
        $('input[type="checkbox"]:checked').each(function (i) {
            $(this).parents('li').remove();
        });

    }
</script>
<style>
    #sortable { list-style-type: none; margin: 0; padding: 0; width:100%;}
    #sortable li { margin: 0 3px 3px 3px; font-size: 1.4em; height: 70px; }
    #sortable li span { position: absolute;}
    .video-detail{
        display: inline-block;
        width: 540px;
        height: 70px;
        line-height: 70px;
        padding-left: 10px;


    }
    .list-cursor{
        display: inline-block;
        width: 50px;
        cursor: move;
        height: 70px;
        line-height: 70px;
        border-right: #CCCCCC dashed thin;
    }
    #sortable input[type=checkbox]{
        height: 70px;
        margin-left: 20px;
        vertical-align:middle;
        margin-left: 1em;
    }

</style>