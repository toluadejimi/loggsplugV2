<?php
    $breadcrumb = getContent('breadcrumb.content', true);
?>
<section class="breadcrumb bg-img" data-background-image="<?php echo e(getImage('assets/images/frontend/breadcrumb/' . @$breadcrumb->data_values->image, '1920x335')); ?>">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="breadcrumb__wrapper">
                    <h2 class="breadcrumb__title"> <?php echo e(__(@$pageTitle)); ?></h2>
                    <ul class="breadcrumb__list">
                        <li class="breadcrumb__item"><a href="<?php echo e(route('home')); ?>" class="breadcrumb__link"><?php echo app('translator')->get('Home'); ?></a> </li>
                        <li class="breadcrumb__item"><i class="fas fa-arrow-right"></i></li>
                        <li class="breadcrumb__item"> <span class="breadcrumb__item-text"> <?php echo e(__(@$pageTitle)); ?> </span> </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/project/aacelogs/core/resources/views/templates/basic/partials/breadcrumb.blade.php ENDPATH**/ ?>