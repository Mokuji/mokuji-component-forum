<?php namespace components\forum; if(!defined('TX')) die('No direct access.'); ?>

<div class="user-information">
  <?php if($data->check('account')): ?>
    
    Logged in as <?php echo $data->account->username; ?>.<br>
    
    <a href="<?php echo $data->edit_profile_link; ?>" class="edit-profile-link">Edit profile</a>
    -
    <a href="<?php echo $data->logout_link; ?>" class="logout-link">Log out</a>
    
    
  <?php else: ?>
    
    <!-- Not logged in.<br> -->
    
    <a href="<?php echo $data->login_link; ?>" class="login-link">Log in</a>
    -
    <a href="<?php echo $data->register_link; ?>" class="register-link">Register</a>
    
  <?php endif; ?>
</div>