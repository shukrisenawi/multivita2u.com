<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Repair Bonus Stokis';
$this->params['breadcrumbs'][] = $this->title;

$reportUrl = Url::to(['cron/repair-bonus-stokis']);
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-tools"></i> Repair Bonus Pendaftaran Stokis Mobile (Type 21)</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Maklumat:</strong> Bonus ini mula berkuatkuasa pada bulan 2 (Februari).
            Syarat: Upline (Mobile Stockist level 4) layak dapat RM5 jika <code>stockist_on = 1</code>
            (iaitu mempunyai >= 5 downline pada bulan sebelumnya).
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <form class="form-inline" method="get">
                    <input type="hidden" name="r" value="cron/repair-bonus-stokis">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari username..." value="<?= Html::encode($search) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <?php if (!$isRepairing): ?>
                    <a href="<?= $reportUrl ?>?repair=1<?= $search ? '&search=' . urlencode($search) : '' ?>" class="btn btn-warning"
                       onclick="return confirm('Anda pasti mahu repair semua data bonus? Tindakan ini akan mengubah data transaksi dan ewallet.')">
                        <i class="fa fa-wrench"></i> Repair Sekarang
                    </a>
                <?php else: ?>
                    <div class="alert alert-success">
                        <i class="fa fa-check-circle"></i> Repair selesai!
                    </div>
                <?php endif; ?>
                <a href="<?= $reportUrl ?>" class="btn btn-secondary">
                    <i class="fa fa-sync"></i> Refresh
                </a>
            </div>
        </div>

        <?php if ($isRepairing && $repairLog): ?>
            <div class="alert alert-warning">
                <h5>Log Repair:</h5>
                <pre style="max-height: 300px; overflow-y: auto;"><?= Html::encode(implode("\n", $repairLog)) ?></pre>
            </div>
        <?php endif; ?>

        <?php if (empty($discrepancies)): ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i> Tiada discrepancy dijumpai. Semua data bonus adalah tepat.
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                Dijumpai <strong><?= count($discrepancies) ?></strong> discrepancy dalam data bonus.
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Ahli Baru</th>
                            <th>Upline (Mobile Stockist)</th>
                            <th>Grand-Upline (Penerima Bonus)</th>
                            <th>Status Bonus</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($discrepancies as $d): ?>
                            <tr class="<?= $d['expected'] ? 'table-danger' : 'table-warning' ?>">
                                <td><?= $i++ ?></td>
                                <td><?= Html::encode($d['period']) ?></td>
                                <td>
                                    <?= Html::encode($d['newUsername']) ?>
                                    <br><small class="text-muted">ID: <?= $d['newId'] ?></small>
                                </td>
                                <td>
                                    <?= Html::encode($d['uplineUsername']) ?>
                                    <br><small class="text-muted">ID: <?= $d['uplineId'] ?></small>
                                </td>
                                <td>
                                    <?= Html::encode($d['grandUplineUsername']) ?>
                                    <br><small class="text-muted">ID: <?= $d['grandUplineId'] ?></small>
                                </td>
                                <td>
                                    <?php if ($d['expected']): ?>
                                        <span class="badge badge-success">Patut Dapat</span>
                                        <br><small class="text-danger">(Tidak ada transaksi)</small>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Tak Patut Dapat</span>
                                        <br><small class="text-warning">(Ada transaksi RM5)</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($d['expected']): ?>
                                        <span class="badge badge-warning">Missing</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Wrong</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!$isRepairing): ?>
                <a href="<?= $reportUrl ?>?repair=1" class="btn btn-warning"
                   onclick="return confirm('Anda pasti mahu repair semua data bonus? Tindakan ini akan mengubah data transaksi dan ewallet.')">
                    <i class="fa fa-wrench"></i> Repair Sekarang
                </a>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
