<?php
/**
 * Template Name: Admin Members
 *
 * Page template for
 *
 * @package Openstrap
 * @since Openstrap 0.1
 */

global $wpdb, $current_user;
if (!is_user_logged_in()) { wp_safe_redirect('/login/'); exit; }
if (! current_user_can('administrator') && ! current_user_can('editor') ){ wp_safe_redirect('/members-only/account/'); exit; }

get_header(); ?>

	<!-- Main Content -->	
	<div class="col-md-12" role="main">
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	        <header class="entry-header">
	                <h1 class="entry-title"><?php the_title(); ?></h1>
	        </header>
	
	        <div class="entry-content">
	                <?php the_content(); ?>
	                	                
					<?php get_template_part('part-templates/accounts', 'individual'); ?>	
					<?php get_template_part('part-templates/accounts', 'bulk'); ?>                
	                
	                
	                <table class="table">
	                	<thead><tr><th>Name</th><th class="text-center hidden-xs">Invoices</th><th class="text-center hidden-xs">Payments</th><th class="text-center">Balance</th></tr></thead><tbody>
	                <?php
	                $users = get_users( [ 'role__in' => [ 'author', 'editor' ] ] );
	                foreach ($users as $user){
	                	$money = get_members_money($user);
	                	$balance = ($money['balance'] > 0) ? '<span class="text-danger"><strong>&pound;'.number_format($money['balance'], 2).'</strong></span>' : '<span class="text-success">&pound;'.number_format(-1*$money['balance'], 2).'</span>';
	                	echo '
						<tr>
							<td><a href="/members-only/account/?cft-member='.$user->user_login.'">'.$user->display_name.'</a></td>
							<td class="text-center hidden-xs">&pound;'.number_format($money['invoices'], 2).'</td>
							<td class="text-center hidden-xs">&pound;'.number_format($money['payments'], 2).'</td>
							<td class="text-center">'.$balance.'</td>
						</tr>';
	                }
	                ?>
	                	</tbody>
	                </table>
	        </div><!-- .entry-content -->
		</article><!-- #post -->
	
	<?php openstrap_custom_pagination(); ?>
	</div>	
	<!-- End Main Content -->	


<?php get_footer(); ?>
<script>
	jQuery(document).ready(function($) {
		$(".date_in").datepicker({
			format : 'dd/mm/yyyy',
			weekStart: 1,
			autoclose : true,
			todayHighlight : true,
			todayBtn : true,
			zIndexOffset : 2000
		});
    });
</script>

