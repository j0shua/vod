<?php echo $main_side_menu; ?>
<div class="container_12 grid_10">
    <h2 class="main-title"><?php echo $title; ?></h2>
    <div class="clearfix">
        <form action="<?php echo $form_action; ?>" method="post" id="normalform">
            <table border="1">
                <thead>
                    <tr>
                        <th> Module </th>
                        <?php foreach($permission['role_title'] as $title){ ?>
                        <th>
                            <?php echo $title; ?>
                            
                        </th>
                        <?php } ?>
                    </tr>
                    
                </thead>

                <tbody>
                    <?php
                    foreach ($permission['rows'] as $v) {
                        foreach ($v['modules'] as $v_module) {
                            ?>
                            <tr>
                                <td><?php echo $v_module['mudule_title']; ?></td>
                                <?php
                                foreach ($v_module['roles'] as $k => $v) {

                                    echo '<td>' . form_checkbox('data[' . $v_module['mid'] . '][' . $k . ']', 'active', $v) . '</td>';
                                }
                                ?>
                            </tr>
                            <?php
                        }
                    }
                    ?>

                </tbody>
            </table>
            <p>
                <input type="submit" value="บันทึก" class="btn-submit" />
            </p>
        </form>
    </div>
</div>
<style>
    table td{
        border: solid 1px black;
        padding: 5px;
    }
     table th{
        border: solid 1px black;
        padding: 5px;
        font-weight: bold;
    }
/*    input[type="checkbox"]{
        height: 0!important;
        margin: 0!important;
    }*/

</style>

