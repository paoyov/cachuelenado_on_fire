<?php
$title = 'Configuración';
?>

<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card mt-4">
                <div class="card-body">
                    <h2 class="card-title">Configuración de cuenta</h2>
                    <p>Actualiza tus preferencias de notificaciones y privacidad.</p>

                    <form method="post" action="<?php echo BASE_URL; ?>maestro/configuracion">
                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="notificaciones_activas" value="1" checked>
                                Notificaciones activas
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="recibir_email" value="1" checked>
                                Recibir notificaciones por email
                            </label>
                        </div>

                        <div class="form-group">
                            <label>
                                <input type="checkbox" name="recibir_whatsapp" value="1">
                                Recibir notificaciones por WhatsApp
                            </label>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Guardar cambios</button>
                            <a href="<?php echo BASE_URL; ?>maestro/dashboard" class="btn btn-link">Volver al panel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card { box-shadow: var(--shadow-lg); }
    .card-title { color: var(--primary-color); }
</style>
