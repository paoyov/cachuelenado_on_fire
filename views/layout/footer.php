    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3><i class="fas fa-fire"></i> Cachueleando On Fire</h3>
                    <p>Conectando clientes con maestros de oficio de confianza en Lima.</p>
                </div>
                <div class="footer-section">
                    <h4>Enlaces</h4>
                    <ul>
                        <li><a href="<?php echo BASE_URL; ?>">Inicio</a></li>
                        <li><a href="<?php echo BASE_URL; ?>buscar">Buscar Maestros</a></li>
                        <li><a href="<?php echo BASE_URL; ?>register">Registrarse</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>Contacto</h4>
                    <p><i class="fas fa-envelope"></i> contacto@cachueleando.com</p>
                    <p><i class="fas fa-phone"></i> +51 999 999 999</p>
                </div>
                <div class="footer-section">
                    <h4>Síguenos</h4>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-facebook"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Cachueleando On Fire. Todos los derechos reservados.</p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script src="<?php echo asset('js/main.js'); ?>"></script>
    
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?php echo asset($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>

