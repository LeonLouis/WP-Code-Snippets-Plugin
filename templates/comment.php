<?php

if ( post_password_required() ) {
	return;
}
$snippets_comment_count = get_comments_number();
?>
<!-- #comments -->