<div class="tab-content">
    <div id="users"  class="tab-pane">
        <table class="tablesorter table table-bordered">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Name</th>
                    <th>Phone #</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if (count($users) > 0) {
                    foreach ($users as $row) {
                        ?>

                        <tr>
                            <td><?php echo $row->email;?></td>
                            <td><?php echo $row->first_name.$row->last_name;?></td>
                            <td><?php echo $row->email;?></td>
                            <td><?php echo $row->email;?></td>
                            <td></td>
                            <td></td>
                            <td></td>
                        </tr>

                        <?php
                    }
                    ?>


                <?php } ?>
            </tbody>
        </table>
    </div>
</div>