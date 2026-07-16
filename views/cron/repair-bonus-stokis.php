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
            Apabila stokis (level 4) daftarkan ahli, yg dapat RM5 ialah
            <strong>pendaftar stokis tersebut</strong> (register_id stokis),
            dengan syarat beliau ada >= 5 pendaftaran pada bulan sebelumnya.
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

        <?php if ($isRepairing && !empty($ewalletChanges)): ?>
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <strong><i class="fa fa-wallet"></i> Senarai Perubahan Ewallet Selepas Repair</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th class="text-right">Ewallet Sebelum Repair</th>
                                <th class="text-right">Penambahan (RM)</th>
                                <th class="text-right">Penolakan (RM)</th>
                                <th class="text-right">Ewallet Selepas Repair</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; $sumAdded = 0; $sumDeducted = 0; foreach ($ewalletChanges as $ec): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><?= Html::encode($ec['username']) ?></td>
                                <td class="text-right">RM<?= number_format($ec['ewallet_before'], 2) ?></td>
                                <td class="text-right text-success">+RM<?= number_format($ec['added'], 2) ?></td>
                                <td class="text-right text-danger">-RM<?= number_format($ec['deducted'], 2) ?></td>
                                <td class="text-right font-weight-bold">RM<?= number_format($ec['ewallet_after'], 2) ?></td>
                            </tr>
                            <?php $sumAdded += $ec['added']; $sumDeducted += $ec['deducted']; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="3" class="text-right"><strong>JUMLAH KESELURUHAN</strong></td>
                                <td class="text-right text-success"><strong>+RM<?= number_format($sumAdded, 2) ?></strong></td>
                                <td class="text-right text-danger"><strong>-RM<?= number_format($sumDeducted, 2) ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <?php             if (empty($discrepancies)): ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i> Tiada discrepancy dijumpai. Semua data bonus adalah tepat.
            </div>
        <?php else: ?>
            <div class="alert alert-danger">
                <i class="fa fa-exclamation-triangle"></i>
                Dijumpai <strong><?= count($discrepancies) ?></strong> discrepancy dalam data bonus.
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h4><?= $totalMissing ?></h4>
                            <strong>Bonus Tak Bayar</strong>
                            <br><small>Perlu dibayar: RM<?= number_format($totalMissing * 5, 2) ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4><?= $totalWrong ?></h4>
                            <strong>Bonus Silap Bayar</strong>
                            <br><small>Kena pulihkan: RM<?= number_format($totalWrong * 5, 2) ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="card bg-info text-white">
                        <div class="card-body py-2">
                            <strong>Pengguna Terlebih Bayar:</strong>
                            <span style="font-size:0.9rem;">
                                <?php
                                $parts = [];
                                foreach ($overpaidUsers as $ou) {
                                    $parts[] = Html::encode($ou['username']) . ' : RM' . number_format($ou['amount'], 2);
                                }
                                echo implode(', ', $parts);
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
                <?php if ($negativeUsers): ?>
                <div class="col-md-12 mt-2">
                    <div class="card bg-danger text-white">
                        <div class="card-body py-2">
                            <strong><i class="fa fa-exclamation-triangle"></i> Akaun Jadi Negatif Jika Repair:</strong>
                            <span style="font-size:0.9rem;">
                                <?php
                                $totalNegatif = 0;
                                $negParts = [];
                                foreach ($negativeUsers as $nu) {
                                    $after = $nu['ewallet'] - $nu['amount'];
                                    $totalNegatif += abs($after);
                                    $negParts[] = Html::encode($nu['username']) . ' (RM' . number_format($nu['ewallet'], 2) . ' - RM' . number_format($nu['amount'], 2) . ' = RM' . number_format($after, 2) . ')';
                                }
                                echo implode(', ', $negParts);
                                ?>
                            </span>
                            <hr class="my-1 border-white">
                            <strong>Jumlah Keseluruhan: RM<?= number_format($totalNegatif, 2) ?></strong>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong><i class="fa fa-list"></i> Ringkasan Bonus Mengikut Penerima</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Penerima</th>
                                <th class="text-right">Ewallet</th>
                                <th class="text-right">Terlebih Bayar</th>
                                <th class="text-right">Perlu Dibayar</th>
                                <th class="text-right">Baki Selepas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; $sumOver = 0; $sumMiss = 0; foreach ($bonusSummary as $bs): ?>
                            <tr class="<?= $bs['overpaid'] && $bs['ewallet'] < $bs['overpaid'] ? 'table-danger' : ($bs['overpaid'] ? 'table-warning' : '') ?>">
                                <td><?= $i++ ?></td>
                                <td><?= Html::encode($bs['username']) ?></td>
                                <td class="text-right">RM<?= number_format($bs['ewallet'], 2) ?></td>
                                <td class="text-right"><?= $bs['overpaid'] ? 'RM' . number_format($bs['overpaid'], 2) : '-' ?></td>
                                <td class="text-right"><?= $bs['missing'] ? 'RM' . number_format($bs['missing'], 2) : '-' ?></td>
                                <td class="text-right">RM<?= number_format($bs['ewallet'] - $bs['overpaid'] + $bs['missing'], 2) ?></td>
                            </tr>
                            <?php $sumOver += $bs['overpaid']; $sumMiss += $bs['missing']; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="2"><strong>JUMLAH KESELURUHAN</strong></td>
                                <td class="text-right"><strong>RM<?= number_format(array_sum(array_column($bonusSummary, 'ewallet')), 2) ?></strong></td>
                                <td class="text-right"><strong>RM<?= number_format($sumOver, 2) ?></strong></td>
                                <td class="text-right"><strong>RM<?= number_format($sumMiss, 2) ?></strong></td>
                                <td class="text-right"><strong>RM<?= number_format(array_sum(array_column($bonusSummary, 'ewallet')) - $sumOver + $sumMiss, 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Ahli Baru</th>
                            <th>Stokis (Pendaftar Ahli)</th>
                            <th>Pendaftar Stokis (Penerima Bonus)</th>
                            <th>Sebab</th>
                            <th>Ewallet</th>
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
                                    <br><small class="text-muted">ID: <?= $d['newId'] ?> (<?= Html::encode(substr($d['newCreatedAt'] ?? '', 0, 7)) ?>)</small>
                                </td>
                                <td>
                                    <?= Html::encode($d['stokisUsername']) ?>
                                    <br><small class="text-muted">ID: <?= $d['stokisId'] ?> (<?= Html::encode(substr($d['stokisCreatedAt'] ?? '', 0, 7)) ?>)</small>
                                </td>
                                <td>
                                    <?= Html::encode($d['recipientUsername']) ?>
                                    <br><small class="text-muted">ID: <?= $d['recipientId'] ?> (<?= Html::encode(substr($d['recipientCreatedAt'] ?? '', 0, 7)) ?>)</small>
                                    <?php if (!$d['expected'] && $d['actual'] && $d['recipientId'] !== $d['corRecipientId']): ?>
                                        <br>✗ patut: <?= Html::encode($d['corRecipientUsername']) ?> (ID:<?= $d['corRecipientId'] ?>)
                                    <?php endif; ?>
                                    </small>
                                </td>
                                <td class="text-center">
                                    <?php if ($d['isNewThisMonth']): ?>
                                        <span class="badge badge-info">Baru</span>
                                    <?php else: ?>
                                        <?= (int)$d['prevDownlines'] ?> org (<?= Html::encode($d['prevDownlineMonth'] ?? '') ?>)
                                    <?php endif; ?>
                                </td>
                                <td class="text-right">RM<?= number_format($d['recipientEwallet'] ?? 0, 2) ?></td>
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
