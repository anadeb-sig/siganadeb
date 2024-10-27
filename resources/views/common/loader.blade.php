
<style>
    .loader-wrapper {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
        visibility: visible;
        opacity: 1;
        transition: visibility 0s, opacity 0.5s linear;
    }

    .loader {
        border: 8px solid #f3f3f3;
        border-radius: 50%;
        border-top: 8px solid #3498db;
        width: 60px;
        height: 60px;
        -webkit-animation: spin 2s linear infinite; /* Safari */
        animation: spin 2s linear infinite;
    }

    @-webkit-keyframes spin {
        0% { -webkit-transform: rotate(0deg); }
        100% { -webkit-transform: rotate(360deg); }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Hide loader when done */
    .loader-wrapper.hidden {
        visibility: hidden;
        opacity: 0;
    }
</style>

<div id="loader" class="loader-wrapper">
    <div class="loader"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    const loader = document.getElementById('loader');
    if (loader) {
        // Masque le loader après le chargement complet de la page
        window.addEventListener('load', function() {
            loader.classList.add('hidden');
            });
        }
    });

</script>
