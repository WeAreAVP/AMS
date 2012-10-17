<div class="tab-content">
    <div id="users"  class="tab-pane">
        <table class="tablesorter table table-bordered">
            <thead>
                <tr>
                    <th>Username</th>
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