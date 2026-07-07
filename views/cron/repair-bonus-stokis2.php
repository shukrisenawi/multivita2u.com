<?php

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Semakan Bonus Stokis Type 21';
$this->params['breadcrumbs'][] = $this->title;

$reportUrl = Url::to(['cron/repair-bonus-stokis2']);
?>

<div class="card">
    <div class="card-header">
        <h3><i class="fa fa-search"></i> Semakan Bonus Pendaftaran Stokis (Type 21)</h3>
    </div>
    <div class="card-body">
        <div class="alert alert-info">
            <strong>Maklumat:</strong> 
            Script ini menyemak bonus untuk <strong>SEMUA BULAN</strong> dalam database.
            <br>
            <strong>Syarat bonus:</strong> Apabila stokis (level 4) daftarkan ahli baru (level 4/5), 
            yang dapat RM5 ialah <strong>pendaftar stokis tersebut</strong> (register_id stokis),
            dengan syarat beliau ada <strong>&gt;= 5 pendaftaran</strong> pada bulan sebelumnya 
            ATAU beliau baru daftar pada bulan semasa.
        </div>

        <div class="row mb-3">
            <div class="col-md-8">
                <form class="form-inline" method="get">
                    <input type="hidden" name="r" value="cron/repair-bonus-stokis2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control"
                               placeholder="Cari username ahli/stokis/penerima..." value="<?= Html::encode($search) ?>">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="submit"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-right">
                <?php if (!$isRepairing && !empty($discrepancies)): ?>
                    <a href="<?= $reportUrl ?>?repair=1<?= $search ? '&search=' . urlencode($search) : '' ?>" 
                       class="btn btn-warning"
                       onclick="return confirm('Anda pasti mahu repair semua data bonus? Tindakan ini akan mengubah data transaksi dan ewallet.')">
                        <i class="fa fa-wrench"></i> Repair Sekarang
                    </a>
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
                Dijumpai <strong><?= count($discrepancies) ?></strong> ketidaksesuaian dalam data bonus.
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h4><?= $totalMissing ?></h4>
                            <strong>Tak Masuk (Patut Dapat)</strong>
                            <br><small>Perlu dibayar: RM<?= number_format($totalMissingAmount, 2) ?></small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card bg-warning text-dark">
                        <div class="card-body text-center">
                            <h4><?= $totalWrong ?></h4>
                            <strong>Silap Masuk (Tak Patut Dapat)</strong>
                            <br><small>Kena pulihkan: RM<?= number_format($totalWrongAmount, 2) ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header">
                    <strong><i class="fa fa-list"></i> Ringkasan Mengikut Penerima Bonus</strong>
                </div>
                <div class="card-body p-0">
                    <table class="table table-bordered table-striped mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Penerima</th>
                                <th class="text-right">Ewallet</th>
                                <th class="text-right">Tak Masuk</th>
                                <th class="text-right">Silap Masuk</th>
                                <th class="text-right">Bersih</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; $sumMissing = 0; $sumWrong = 0; foreach ($summary as $s): ?>
                            <tr class="<?= $s['wrong'] && $s['ewallet'] < $s['wrong'] ? 'table-danger' : ($s['wrong'] ? 'table-warning' : '') ?>">
                                <td><?= $i++ ?></td>
                                <td><?= Html::encode($s['username']) ?></td>
                                <td class="text-right">RM<?= number_format($s['ewallet'], 2) ?></td>
                                <td class="text-right"><?= $s['missing'] ? 'RM' . number_format($s['missing'], 2) : '<span class="text-muted">-</span>' ?></td>
                                <td class="text-right"><?= $s['wrong'] ? 'RM' . number_format($s['wrong'], 2) : '<span class="text-muted">-</span>' ?></td>
                                <td class="text-right"><strong>RM<?= number_format($s['ewallet'] + $s['missing'] - $s['wrong'], 2) ?></strong></td>
                            </tr>
                            <?php $sumMissing += $s['missing']; $sumWrong += $s['wrong']; endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr class="font-weight-bold">
                                <td colspan="2"><strong>JUMLAH</strong></td>
                                <td class="text-right"><strong>RM<?= number_format(array_sum(array_column($summary, 'ewallet')), 2) ?></strong></td>
                                <td class="text-right"><strong>RM<?= number_format($sumMissing, 2) ?></strong></td>
                                <td class="text-right"><strong>RM<?= number_format($sumWrong, 2) ?></strong></td>
                                <td class="text-right"><strong>RM<?= number_format(array_sum(array_column($summary, 'ewallet')) + $sumMissing - $sumWrong, 2) ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <h5 class="mt-4 mb-3"><i class="fa fa-exclamation-circle text-danger"></i> Senarai Penuh Discrepancy</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Ahli Baru</th>
                            <th>Stokis</th>
                            <th>Penerima Bonus</th>
                            <th>Daftar Bulan Lalu</th>
                            <th>Status</th>
                            <th>Jenis</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; foreach ($discrepancies as $d): ?>
                            <tr class="<?= $d['shouldGet'] ? 'table-danger' : 'table-warning' ?>">
                                <td><?= $i++ ?></td>
                                <td><?= Html::encode($d['period']) ?></td>
                                <td>
                                    <strong><?= Html::encode($d['newMemberUsername']) ?></strong>
                                    <br><small class="text-muted">ID: <?= $d['newMemberId'] ?> | <?= Html::encode(substr($d['newMemberCreated'], 0, 10)) ?></small>
                                </td>
                                <td>
                                    <?= Html::encode($d['stokisUsername']) ?>
                                    <br><small class="text-muted">ID: <?= $d['stokisId'] ?></small>
                                </td>
                                <td>
                                    <strong><?= Html::encode($d['recipientUsername']) ?></strong>
                                    <br><small class="text-muted">ID: <?= $d['recipientId'] ?> | RM<?= number_format($d['recipientEwallet'], 2) ?></small>
                                </td>
                                <td class="text-center">
                                    <?php if ($d['isNewThisMonth']): ?>
                                        <span class="badge badge-info">Baru Daftar</span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary"><?= (int)$d['prevDownlineCount'] ?> ahli</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($d['shouldGet']): ?>
                                        <span class="badge badge-success"><i class="fa fa-check"></i> Patut Dapat</span>
                                        <br><small class="text-danger"><strong>TIDAK ADA</strong> transaksi</small>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fa fa-times"></i> Tak Patut Dapat</span>
                                        <br><small class="text-warning"><strong>ADA</strong> transaksi RM5</small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($d['shouldGet'] && !$d['actualGet']): ?>
                                        <span class="badge badge-warning">Tak Masuk</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">Silap Masuk</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if (!$isRepairing): ?>
                <div class="mt-3">
                    <a href="<?= $reportUrl ?>?repair=1" class="btn btn-warning btn-lg"
                       onclick="return confirm('AMARDA: Anda pasti mahu repair semua data bonus?\n\n- Data yang tak masuk akan ditambah\n- Data yang silap masuk akan dibuang (ewallet ditolak)\n\nTindakan ini tidak boleh dipulihkan.')">
                        <i class="fa fa-wrench"></i> Repair Semua Discrepancy
                    </a>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>
