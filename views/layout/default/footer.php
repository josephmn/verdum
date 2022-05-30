<div class="sidenav-overlay"></div>
<div class="drag-target"></div>

<!-- BEGIN: Footer-->
<footer class="footer footer-static footer-light">
    <p class="clearfix mb-0"><span class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; 2021<a class="ml-25" href="https://www.mundoaltomayo.com" target="_blank">Mundo Altomayo</a><span class="d-none d-sm-inline-block">, All rights Reserved. Version 1.0</span></span></p>
</footer>
<button class="btn btn-success btn-icon scroll-top" type="button"><i data-feather="arrow-up"></i></button>
<!-- END: Footer-->

<!-- BEGIN: Llamada a JS-->
<?php if (isset($_layoutParams['jsSp']) && count($_layoutParams['jsSp'])) : ?>
    <?php foreach ($_layoutParams['jsSp'] as $layoutjsSp) : ?>
        <script src="<?php echo  $layoutjsSp ?>" type="text/javascript"></script>
    <?php endforeach; ?>
<?php endif; ?>
<!-- END: Llamada a JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>

<?php if (isset($_layoutParams['js']) && count($_layoutParams['js'])) : ?>
    <?php foreach ($_layoutParams['js'] as $layout) : ?>
        <script src="<?php echo  $layout ?>" type="text/javascript"></script>
    <?php endforeach; ?>
<?php endif; ?>

</body>
<!-- END: Body-->

</html>