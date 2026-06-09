<?php
$completion = 0;

if(!empty($user->first_name))
    $completion += 10;

if(!empty($user->last_name))
    $completion += 10;

if(!empty($user->user_email))
    $completion += 10;

if(get_user_meta($user_id,'billing_phone',true))
    $completion += 15;

if(get_user_meta($user_id,'billing_address_1',true))
    $completion += 20;

if(get_user_meta($user_id,'shipping_address_1',true))
    $completion += 20;

if(get_avatar_url($user_id))
    $completion += 15;
?>
<div class="wcmamtx-profile-wizard">

    <div class="wcmamtx-profile-header">
        <div class="wcmamtx-profile-info">
            <h3>Complete Your Profile</h3>
            <p>Unlock all account features by completing your profile.</p>
        </div>

        <div class="wcmamtx-profile-percent">
            <span>65%</span>
        </div>
    </div>

    <div class="wcmamtx-progress-bar">
        <div class="wcmamtx-progress-fill" style="width:65%;"></div>
    </div>

    <div class="wcmamtx-profile-tasks">

        <a href="#" class="wcmamtx-task completed">
            <span class="task-icon">✓</span>
            <span class="task-label">Personal Information Added</span>
        </a>

        <a href="#" class="wcmamtx-task completed">
            <span class="task-icon">✓</span>
            <span class="task-label">Email Verified</span>
        </a>

        <a href="#" class="wcmamtx-task pending">
            <span class="task-icon">3</span>
            <span class="task-label">Upload Profile Picture</span>
        </a>

        <a href="#" class="wcmamtx-task pending">
            <span class="task-icon">4</span>
            <span class="task-label">Add Billing Address</span>
        </a>

        <a href="#" class="wcmamtx-task pending">
            <span class="task-icon">5</span>
            <span class="task-label">Add Shipping Address</span>
        </a>

    </div>

</div>