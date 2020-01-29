<?php if ($action == "completed") { ?>
<P class="alert alert-success"><?php echo $message ?></P>
<?php } ?>
<?php if ($messageWarning != "") { ?>
<P class="alert alert-warning"><?php echo $messageWarning ?></P>
<?php } ?>
<?php if ($error) { ?>
<P class="alert alert-danger"><?php echo $message ?></P>
<?php } ?>