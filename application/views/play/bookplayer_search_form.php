
<div class="grid_12" style="min-height: 600px;">
    <h1>{form_title}</h1>
    <div id="center-box">
        <form class="normal-form" id="form-main" action="<?php echo $form_action ?>" method="post" autocomplete="off">
   

            <p>
                <label for="search_text">เลขที่สื่อ</label><input type="text" id="search_text" name="search_text" value="" size="16" />
            </p>


            <input id="btn-center" type="submit" value="ค้นหา" />


        </form>
    </div>
</div>

<style>
    h1{
        width: 400px;
        margin: auto;
        text-align: center;
        padding: 10px 10px;
        border: 1px solid #CCCCCC;
        margin-top: 170px;
    }
    #center-box{
        margin: auto;
        width: 400px;
        padding: 20px 10px;

        border-bottom: 1px solid #CCCCCC;
        border-left: 1px solid #CCCCCC;
        border-right: 1px solid #CCCCCC;
    }
    #btn-center{
        margin-top: 20px;
        margin-left: 155px;

    }

</style>