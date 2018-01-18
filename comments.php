<?php
/**
 * Comments template
 *
 * @version 1.3.2
 *
 * @package Schlicht
 */

if ( post_password_required() ) {
	return;
} ?>
<aside aria-label="<?php _e( 'Comments and trackbacks area', 'schlicht' ); ?>" class="comments-area">
	<?php if ( have_comments() ) {
		// We cannot use the second parameter from comments_template()
		// to separate the comments, because if the comments are
		// broken on multiple pages, the count() would only return
		// the number of comments and trackbacks which are displayed
		// on the current page, not the total.
		//
		// Because of that, we use our own function to get the comments by type.
		$comments_by_type = schlicht_get_comments_by_type();
		if ( ! empty( $comments_by_type['comment'] ) ) {
			// Save the comment count.
			$comment_number = count( $comments_by_type['comment'] ); ?>
			<h2 class="comments-title" id="comments-title">
				<?php /* translators: Title for comment list. 1=comment number, 2=post title */
				printf( _n(
					'%1$s Comment on “%2$s”',
					'%1$s Comments on “%2$s”',
					$comment_number,
					'schlicht'
				), number_format_i18n( $comment_number ),
					get_the_title() ) ?>
			</h2>

			<ul class="commentlist">
				<?php
				// We need to specify the per_page key, because otherwise
				// the separated reactions would not be broken correctly into
				// multiple pages.
				wp_list_comments( [
					'callback' => 'schlicht_comments',
					'style'    => 'ul',
					'type'     => 'comment',
					'per_page' => ( isset( $comment_args['number'] ) ? $comment_args['number'] : - 1 ),
				] ); ?>
			</ul>
		<?php } // End if().
		if ( ! empty( $comments_by_type['pings'] ) ) {
			// Save the count of trackbacks and pingbacks.
			$trackback_number = count( $comments_by_type['pings'] ); ?>
			<h2 class="trackbacks-title" id="trackbacks-title">
				<?php /* translators: Title for trackback list. 1=trackback number, 2=post title */
				printf( _n(
					'%1$s Trackback on “%2$s”',
					'%1$s Trackbacks on “%2$s”',
					$trackback_number,
					'schlicht'
				), number_format_i18n( $trackback_number ), get_the_title() ) ?>
			</h2>

			<ul class="commentlist">
				<?php
				// Display the pings in short version.
				wp_list_comments( [
					'type'       => 'pings',
					'short_ping' => true,
					'per_page'   => ( isset( $comment_args['number'] ) ? $comment_args['number'] : - 1 ),
				] ); ?>
			</ul>
		<?php } // End if().

		// Only show the comments navigation, of one of the reaction counts
		// is larger than the set number of comments per page. Necessary because
		// of our separation. Otherwise, the navigation would also be displayed if
		// we should display 4 comments per page and have 3 comments and 2 trackbacks.
		if (
			( ( isset( $trackback_number ) && isset( $comment_args['number'] ) ) && $trackback_number > $comment_args['number'] )
			|| ( ( isset( $comment_number ) && isset( $comment_args['number'] ) ) && $comment_number > $comment_args['number'] )
		) {
			the_comments_navigation();
		} // End if().

		// Display comments closed hint if comments are closed but we already have comments.
		if ( ! comments_open() && get_comments_number() ) { ?>
			<p class="nocomments"><?php _e( 'Comments are closed.', 'schlicht' ); ?></p>
		<?php } // End if().
	} // End if().

	// Display comment form with modified submit label.
	comment_form( [ 'label_submit' => __( 'Submit Comment', 'schlicht' ) ] ); ?>
</aside>
