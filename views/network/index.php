<?php

use app\models\User;
use yii\helpers\Html;

$this->title = 'Network';
$this->params['breadcrumbs'][] = $this->title;

$firstUnit = User::find()->where(['id' => $id])->asArray()->one();
if ($firstUnit) {
    $userId = $firstUnit['id'];
    $user = $firstUnit;
    $userSelect = $userId;
    ?>
    <link rel="stylesheet" href="js/dtree/dtree.css" type="text/css" />
    <script type="text/javascript" src="js/dtree/dtree.js"></script>

    <div class="network-tree-shell">
        <section class="network-tree-hero">
            <div class="network-tree-hero__eyebrow">Rangkaian Ahli</div>
            <h1 class="network-tree-hero__title"><?= Html::encode($user['name']) ?> <span>(<?= Html::encode($user['username']) ?>)</span></h1>
            <p class="network-tree-hero__desc">Semak struktur downline dengan paparan lebih kemas, latar lebih bersih, dan carian pantas berdasarkan username atau nama.</p>
        </section>

        <?php if ($userSelect) { ?>
            <section class="network-tree-panel card">
                <div class="card-body">
                    <div class="network-tree-toolbar">
                        <div class="network-tree-toolbar__actions">
                            <a href="javascript:void(0);" class="btn btn-light network-tree-btn" onclick="d.openAll(); return false;">Buka Semua</a>
                            <a href="javascript:void(0);" class="btn btn-light network-tree-btn" onclick="d.closeAll(); return false;">Tutup Semua</a>
                        </div>
                        <div class="network-tree-search">
                            <i class="fa fa-search"></i>
                            <input type="text" id="network-tree-search" class="form-control" placeholder="Cari username atau nama">
                        </div>
                    </div>

                    <div class="network-tree-frame">
                        <div class="dtree" id="network-tree-view">
                            <script type="text/javascript">
                                <?php
                                $uplineIds = [$userSelect ? $userSelect : 0];
                                $nodeLevels = [$userSelect => 0];

                                $formatTreeLabel = static function (array $node, int $level): string {
                                    $name = $node['name'] ?? '';
                                    $username = strtolower($node['username'] ?? '');

                                    return "&nbsp;&nbsp;" . addslashes($name) .
                                        " <em>(" . addslashes($username) . ")</em> - level " . $level;
                                };
                                ?>
                                d = new dTree('d');

                                d.add(<?= $userSelect ?>, -1, "<?= $formatTreeLabel($user, 0) ?>");

                                <?php
                                $limitUpline = $rows;
                                $i = 0;
                                $alldownline = [];
                                while (!empty($uplineIds) && $i < $limitUpline) {
                                    $i++;
                                    $queryDownline = User::find()
                                        ->select(['id', 'upline_id', 'name', 'username'])
                                        ->where(['upline_id' => $uplineIds, 'level_id' => 5])
                                        ->asArray()
                                        ->all();
                                    $uplineIds = [];
                                    foreach ($queryDownline as $downline) {
                                        $uplineIds[] = $downline['id'];
                                        $nodeLevels[$downline['id']] = ($nodeLevels[$downline['upline_id']] ?? 0) + 1;
                                        $alldownline[] = $downline;
                                    }
                                }

                                foreach ($alldownline as $listUnit) {
                                    echo "d.add(" . $listUnit['id'] .
                                        "," . $listUnit['upline_id'] .
                                        ",\"" . $formatTreeLabel($listUnit, $nodeLevels[$listUnit['id']] ?? 0) .
                                        "\");\n";
                                }
                                ?>
                                document.write(d);

                                (function () {
                                    function getTreeRoot(wrapper) {
                                        return wrapper.querySelector(':scope > .dtree') || wrapper;
                                    }

                                    function getChildClip(node) {
                                        if (!node) {
                                            return null;
                                        }

                                        var next = node.nextElementSibling;
                                        if (next && next.classList.contains('clip')) {
                                            return next;
                                        }

                                        return null;
                                    }

                                    function filterNodes(container, term) {
                                        var children = Array.prototype.slice.call(container.children);
                                        var matchedAny = false;

                                        for (var i = 0; i < children.length; i++) {
                                            var node = children[i];
                                            if (!node.classList.contains('dTreeNode')) {
                                                continue;
                                            }

                                            var clip = getChildClip(node);
                                            var link = node.querySelector('a.node, a.nodeSel');
                                            var ownText = (node.textContent || '').toLowerCase();
                                            var ownMatch = term === '' || ownText.indexOf(term) !== -1;
                                            var childMatch = clip ? filterNodes(clip, term) : false;
                                            var isMatch = ownMatch || childMatch;

                                            node.style.display = isMatch ? '' : 'none';

                                            if (link) {
                                                link.classList.toggle('network-tree-match', term !== '' && ownMatch);
                                            }

                                            if (clip) {
                                                if (!clip.dataset.originalDisplay) {
                                                    clip.dataset.originalDisplay = clip.style.display || 'block';
                                                }
                                                clip.style.display = term === ''
                                                    ? clip.dataset.originalDisplay
                                                    : (childMatch ? 'block' : 'none');
                                            }

                                            matchedAny = matchedAny || isMatch;
                                        }

                                        return matchedAny;
                                    }

                                    function initTreeSearch() {
                                        var input = document.getElementById('network-tree-search');
                                        var wrapper = document.getElementById('network-tree-view');
                                        if (!input || !wrapper) {
                                            return;
                                        }

                                        var tree = getTreeRoot(wrapper);

                                        var clips = tree.querySelectorAll('.clip');
                                        clips.forEach(function (clip) {
                                            clip.dataset.originalDisplay = clip.style.display || 'block';
                                        });

                                        input.addEventListener('input', function () {
                                            var term = this.value.trim().toLowerCase();
                                            if (term !== '') {
                                                d.openAll();
                                            }
                                            filterNodes(tree, term);
                                            if (term === '') {
                                                clips.forEach(function (clip) {
                                                    clip.style.display = clip.dataset.originalDisplay || 'block';
                                                });
                                            }
                                        });
                                    }

                                    if (document.readyState === 'loading') {
                                        document.addEventListener('DOMContentLoaded', initTreeSearch);
                                    } else {
                                        initTreeSearch();
                                    }
                                })();
                            </script>
                        </div>
                    </div>
                </div>
            </section>
        <?php } else { ?>
            <div class="network-tree-empty">User tidak dijumpai.</div>
        <?php } ?>
    </div>
<?php } else { ?>
    <div class="network-tree-empty">Tiada data rangkaian untuk dipaparkan.</div>
<?php } ?>
