<?php 
	$fiAdd_adsLeftID = get_option('fiAdd_adsLeftID');
?>
<?php if($fiAdd_adsLeftID) : ?>
<div class="fiadds">
	<div class="fiadd-left-ads">
		<?php echo $fiAdd_adsLeftID;?>
	</div>
</div>
<?php  endif; ?>

<?php 
	$fiAdd_adsRightID = get_option('fiAdd_adsRightID');
?>
<?php if($fiAdd_adsRightID) : ?>
<div class="fiadds">
	<div class="fiadd-right-ads">
		<?php echo $fiAdd_adsRightID;?>
	</div>
</div>
<?php  endif; ?>