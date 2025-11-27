<?php
$title = 'Reportes - Imprimir';
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Reportes - Imprimir</title>
  <link href="<?php echo BASE_URL; ?>assets/css/style.css" rel="stylesheet">
  <style>
    body { font-family: Arial, Helvetica, sans-serif; color: #222; }
    .report-wrapper { max-width: 1100px; margin: 20px auto; }
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 8px 10px; border: 1px solid #ddd; font-size: 13px; }
    th { background: #f5f5f5; text-align: left; }
    h2 { margin-bottom: 6px; }
    .meta { color: #666; margin-bottom: 18px; }
    @media print { .no-print { display: none; } }
  </style>
</head>
<body>
  <div class="report-wrapper">
    <h2>Reportes del Sistema</h2>
    <div class="meta">Generado: <?php echo date('d/m/Y H:i'); ?> &middot; Total: <?php echo isset($reportes) ? count($reportes) : 0; ?></div>

    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Reportado por</th>
          <th>Reportado a</th>
          <th>Tipo</th>
          <th>Motivo</th>
          <th>Estado</th>
          <th>Fecha</th>
        </tr>
      </thead>
      <tbody>
        <?php if (isset($reportes) && count($reportes) > 0): ?>
          <?php foreach ($reportes as $r): ?>
            <tr>
              <td><?php echo $r['id']; ?></td>
              <td><?php echo htmlspecialchars($r['reportado_por_nombre']); ?></td>
              <td><?php echo htmlspecialchars($r['reportado_a_nombre']); ?></td>
              <td><?php echo htmlspecialchars(ucfirst($r['tipo'])); ?></td>
              <td><?php echo htmlspecialchars($r['motivo']); ?></td>
              <td><?php echo htmlspecialchars($r['estado']); ?></td>
              <td><?php echo $r['fecha_reporte']; ?></td>
            </tr>
          <?php endforeach; ?>
        <?php else: ?>
          <tr><td colspan="7">No hay reportes registrados.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>

    <div style="margin-top:16px;" class="no-print">
      <button onclick="window.print();" class="btn btn-primary">Imprimir / Guardar como PDF</button>
      <a href="<?php echo BASE_URL; ?>admin/reportes" class="btn btn-secondary">Volver</a>
    </div>
  </div>
</body>
</html>
