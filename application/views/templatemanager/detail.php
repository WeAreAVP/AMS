<?php
if (count($template_detail) > 0) {
    ?>
    <center>
        <table class="table table-bordered zebra-striped" style="width: 50%;">
            <tr>
                <td>
                   System Id
                </td>
                <td> <?php echo $template_detail->system_id; ?></td>
            </tr>
            <tr>
                <td>
                   Subject
                </td>
                <td><?php echo $template_detail->subject; ?>

                </td>
            </tr>
            <tr>
                <td>
                    Plain Body
                </td>
                <td><?php echo $template_detail->body_plain; ?>
            </tr>
            <tr>
                <td>
                   Html Body
                </td>
                <td><?php echo $template_detail->body_html; ?>
            </tr>
            <tr>
                <td>
                   Email Type
                </td>
                <td><?php echo $template_detail->email_type; ?>
                    
                </td>
            </tr>
            <tr>
                <td>
                   Email From 
                </td>
                <td><?php echo $template_detail->email_from; ?>
            </tr>
             <tr>
                <td>
                   Reply To
                </td>
                <td><?php echo $template_detail->reply_to; ?>
            </tr>
            <tr>
                <td>
                   Replaceables
                </td>
                <td><?php echo $template_detail->replaceables; ?>
            </tr>
        </table></center>
    <?php ?>
    <?php
} else {
    echo '<h1>The requested template not found</h1>';
}
?>