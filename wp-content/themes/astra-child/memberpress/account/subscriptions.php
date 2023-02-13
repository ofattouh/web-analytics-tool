<?php
if(!defined('ABSPATH')) {die('You are not allowed to call this page directly.');}

MeprHooks::do_action('mepr_before_account_subscriptions', $mepr_current_user);
$cap = get_user_capability();

if(!empty($subscriptions)) {
  $alt = false;
  ?>
  <div class="mp_wrapper">
    <table id="mepr-account-subscriptions-table" class="mepr-account-table">
      <thead>
        <tr>
          <th><?php _ex('OSIR assessment', 'ui', 'memberpress'); ?></th>
          <th><?php _ex('Status', 'ui', 'memberpress'); ?></th>
          <?php if ( !empty($cap['gf_moderator']) || !empty($cap['corporate_parent_account_moderator']) || !empty($cap['administrator']) || !empty($cap['designer']) || !empty($cap['pshsa_analytics_reports']) ): ?>
            <th><?php _ex('Participant List', 'ui', 'memberpress'); ?></th>
            <?php MeprHooks::do_action('mepr-account-subscriptions-th', $mepr_current_user, $subscriptions); ?>
          <?php endif; ?>
        </tr>
      </thead>
      <tbody>
        <?php
        foreach($subscriptions as $s):
          if(trim($s->sub_type) == 'transaction') {
            $is_sub   = false;
            $txn      = $sub = new MeprTransaction($s->id);
            $pm       = $txn->payment_method();
            $prd      = $txn->product();
            $group    = $prd->group();
            $default  = _x('Never', 'ui', 'memberpress');
            if($txn->txn_type == MeprTransaction::$fallback_str && $mepr_current_user->subscription_in_group($group)) {
              //Skip fallback transactions when user has an active sub in the fallback group
              continue;
            }
          }
          else {
            $is_sub   = true;
            $sub      = new MeprSubscription($s->id);
            $txn      = $sub->latest_txn();
            $pm       = $sub->payment_method();
            $prd      = $sub->product();
            $group    = $prd->group();

            if($txn == false || !($txn instanceof MeprTransaction) || $txn->id <= 0) {
              $default = _x('Unknown', 'ui', 'memberpress');
            }
            else if(trim($txn->expires_at) == MeprUtils::db_lifetime() or empty($txn->expires_at)) {
              $default = _x('Never', 'ui', 'memberpress');
            }
            else {
              $default = _x('Unknown', 'ui', 'memberpress');
            }
          }

          $mepr_options = MeprOptions::fetch();
          $alt          = !$alt; // Facilitiates the alternating lines
        ?>
          <tr id="mepr-subscription-row-<?php echo $s->id; ?>" class="mepr-subscription-row <?php echo (isset($alt) && !$alt)?'mepr-alt-row':''; ?>">
            <td data-label="<?php _ex('Membership', 'ui', 'memberpress'); ?>">
              <!-- MEMBERSHIP ACCESS URL -->
              <?php if(isset($prd->access_url) && !empty($prd->access_url)): ?>
                <div class="mepr-account-product"><a href="<?php echo stripslashes($prd->access_url); ?>"><?php echo MeprHooks::apply_filters('mepr-account-subscr-product-name', $prd->post_title, $txn); ?></a></div>
              <?php else: ?>
                <div class="mepr-account-product"><?php echo MeprHooks::apply_filters('mepr-account-subscr-product-name', $prd->post_title, $txn); ?></div>
              <?php endif; ?>

              <?php if($txn != false && $txn instanceof MeprTransaction && !$txn->is_sub_account()): ?>
                <div class="mepr-account-subscr-id"><?php echo $s->subscr_id; ?></div>
              <?php endif; ?>
            </td>

            <!-- Changed membership status label -->
            <?php $s_mystatus = ($s->active === '<span class="mepr-active">Yes</span>')? 'Active': 'Closed'; ?>
            <td data-label="<?php _ex('Active', 'ui', 'memberpress'); ?>"><div class="mepr-account-active"><?php echo $s_mystatus; ?></div></td>
            
            <?php if ( !empty($cap['gf_moderator']) || !empty($cap['corporate_parent_account_moderator']) || !empty($cap['administrator']) || !empty($cap['designer']) || !empty($cap['pshsa_analytics_reports']) ): ?>
            <td data-label="<?php _ex('Actions', 'ui', 'memberpress'); ?>">
                <div class="mepr-account-actions">
                  <?php
                  // changed by Omar. GF moderators are sub accounts in memberpress, bypass check
                  // if($txn != false && $txn instanceof MeprTransaction && ($txn->is_sub_account() || $txn->txn_type == MeprTransaction::$fallback_str)) {
                  if($txn != false && $txn instanceof MeprTransaction && $txn->txn_type == MeprTransaction::$fallback_str) {
                    echo '--';
                  }
                  else {
                    if( $is_sub && $pm instanceof MeprBaseRealGateway &&
                        ( $s->status == MeprSubscription::$active_str ||
                          $s->status == MeprSubscription::$suspended_str ||
                          strpos($s->active, 'mepr-active') !== false ) ) {
                      $subscription = new MeprSubscription($s->id);

                      if(!$subscription->in_grace_period()) { //Don't let people change shiz until a payment has come through yo
                        $pm->print_user_account_subscription_row_actions($subscription);
                      }
                    }
                    elseif(!$is_sub && !empty($prd->ID)) {
                      if($prd->is_renewable() && $prd->is_renewal()) {
                        ?>
                          <a href="<?php echo $prd->url(); ?>" class="mepr-account-row-action mepr-account-renew"><?php _ex('Renew', 'ui', 'memberpress'); ?></a>
                        <?php
                      }

                      if($txn != false && $txn instanceof MeprTransaction && $group !== false && strpos($s->active, 'mepr-inactive') === false) {
                        MeprAccountHelper::group_link($txn);
                      }
                      elseif(/*$group !== false &&*/ strpos($s->active, 'mepr-inactive') !== false /*&& !$prd->is_renewable()*/) {
                        if($prd->can_you_buy_me()) {
                          MeprAccountHelper::purchase_link($prd);
                        }
                      }
                    }
                    else {
                      if($prd->can_you_buy_me()) {
                        if($group !== false && $txn !== false && $txn instanceof MeprTransaction) {
                          $sub_in_group   = $mepr_current_user->subscription_in_group($group);
                          $life_in_group  = $mepr_current_user->lifetime_subscription_in_group($group);

                          if(!$sub_in_group && !$life_in_group) { //$prd is in group, but user has no other active subs in this group, so let's show the change plan option
                            MeprAccountHelper::purchase_link($prd, _x('Re-Subscribe', 'ui', 'memberpress'));
                            MeprAccountHelper::group_link($txn);
                          }
                        }
                        else {
                          MeprAccountHelper::purchase_link($prd);
                        }
                      }
                    }

                    // not needed
                    // MeprHooks::do_action('mepr-account-subscriptions-actions', $mepr_current_user, $s, $txn, $is_sub);
                    my_mepr_account_subscriptions_actions_func($mepr_current_user, $s, $txn, $is_sub);
                  }
                  ?>
                  &zwnj; <!-- Responsiveness when no actions present -->
                </div>
            </td>
            <?php MeprHooks::do_action('mepr-account-subscriptions-td', $mepr_current_user, $s, $txn, $is_sub); ?>
            <?php endif; ?>
          </tr>
        <?php endforeach; ?>
        <?php MeprHooks::do_action('mepr-account-subscriptions-table', $mepr_current_user, $subscriptions); ?>
      </tbody>
    </table>
    <div id="mepr-subscriptions-paging">
      <?php if($prev_page): ?>
        <a href="<?php echo "{$account_url}{$delim}currpage={$prev_page}"; ?>">&lt;&lt; <?php _ex('Previous Page', 'ui', 'memberpress'); ?></a>
      <?php endif; ?>
      <?php if($next_page): ?>
        <a href="<?php echo "{$account_url}{$delim}currpage={$next_page}"; ?>" style="float:right;"><?php _ex('Next Page', 'ui', 'memberpress'); ?> &gt;&gt;</a>
      <?php endif; ?>
    </div>
    <div style="clear:both"></div>
  </div>
  <?php
}
else {
  echo '<div class="mepr-no-active-subscriptions">' . _x('You have no active subscriptions to display.', 'ui', 'memberpress') . '</div>';
}

MeprHooks::do_action('mepr_account_subscriptions', $mepr_current_user);
