<style>
    .success {
        position: fixed;
        top: 75px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        width: 100%;
    }
    .error {
        position: fixed;
        top: 75px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 100;
        width: 100%;
    }
</style>
    <?php if (isset($successMessage)): ?>
        <div class="alert alert-success text-center success" role="alert">
            <?php echo $successMessage; ?>
        </div>
    <?php endif; ?>
    <?php if (isset($errorMessage)): ?>
        <div class="alert alert-danger text-center error" role="alert">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>
    
<script>
    setTimeout(() => {
        document.querySelector('.success') && (document.querySelector('.success').style.display = 'none');
        document.querySelector('.error') && (document.querySelector('.error').style.display = 'none');
    }, 3000);
</script>