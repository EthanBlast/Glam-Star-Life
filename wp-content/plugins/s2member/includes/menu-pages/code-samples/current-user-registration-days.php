<?php echo S2MEMBER_CURRENT_USER_REGISTRATION_DAYS; ?>
This may output something like: 120 ( 120 days is approx 4 months )

Here's how you Drip Content. The longer they've been a Member, the more they get.

<?php if(S2MEMBER_CURRENT_USER_IS_LOGGED_IN_AS_MEMBER){ ?>

	This is some content that will be displayed to all Members.
	
	<?php if(S2MEMBER_CURRENT_USER_REGISTRATION_DAYS >= 30){ ?>
		Drip content to Members who are more than 30 days old.
	<?php } ?>
	
	<?php if(S2MEMBER_CURRENT_USER_REGISTRATION_DAYS >= 60){ ?>
		Drip content to Members who are more than 60 days old.
	<?php } ?>
	
	<?php if(S2MEMBER_CURRENT_USER_REGISTRATION_DAYS >= 90){ ?>
		Drip content to Members who are more than 90 days old.
	<?php } ?>

<?php } ?>

---- s2member Shortcode Equivalent ----

[s2Get constant="S2MEMBER_CURRENT_USER_REGISTRATION_DAYS" /]

There is NO Shortcode equivalent for this logic yet. Coming soon.