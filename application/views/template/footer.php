        </div>
    </div>
</div>
</body>
    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo base_url('assets/plugins/jquery/jquery.min.js'); ?>"></script>
    <script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>

    <script>
        $(".sidebar ul li").on('click', function () {
            $(".sidebar ul li.active").removeClass('active');
            $(this).addClass('active');
        });

        $('.open-btn').on('click', function () {
            $('.sidebar').addClass('active');

        });


        $('.close-btn').on('click', function () {
            $('.sidebar').removeClass('active');

        });
    </script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo base_url('assets/plugins/jquery-easing/jquery.easing.min.js'); ?>"></script>

    <!-- Page level plugins -->
    <script src="<?php echo base_url('assets/plugins/chart.js/Chart.min.js'); ?>"></script>
</body>

</html>