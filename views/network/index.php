<?php
$this->title = "Network";

$this->params['breadcrumbs'][] = $this->title;

use app\models\User;

$firstUnit = User::find()->where(['id' => $id])->asArray()->one();
if ($firstUnit) {
    $userId = $firstUnit['id'];

    $user = $firstUnit;
    $userSelect = $userId;
    ?>
    <link rel="StyleSheet" href="js/dtree/dtree.css" type="text/css" />
    <script type="text/javascript" src="js/dtree/dtree.js"></script>
    <h2><?= $user['name'] . ' ( ' . $user['username'] . ' )' ?></h2>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td height="482" valign="top">

                <?php if ($userSelect) { ?>
                    <div class="dtree">
                        <p><a href="javascript: d.openAll();">Show All</a> | <a href="javascript: d.closeAll();">Close All</a>
                        </p>
                        <script type="text/javascript">
                            <!--
                            <?php $uplineIds = [$userSelect ? $userSelect : 0]; ?>
                            d = new dTree('d');

                            d.add(<?= $userSelect ?>, -1,"&nbsp;&nbsp;<?= $user['name'] . " <em> (" . strtolower($user['username'] . ") </em>") ?>");

                            <?php
                                    $limitUpline = $rows;
                                    $i = 0;
                                    $alldownline = array();
                                    while (!empty($uplineIds) && $i < $limitUpline){
                                        $i++;
                                        $query_downline = User::find()
                                            ->select(['id', 'upline_id', 'name', 'username'])
                                            ->where(['upline_id' => $uplineIds, 'level_id' => 5])
                                            ->asArray()
                                            ->all();
                                        $uplineIds = array();
                                        foreach ($query_downline as $downline) {
                                            $uplineIds[] = $downline['id'];
                                            $alldownline[] = $downline;
                                        }
                                    }

                                    if (count($alldownline) > 0) {
                                        foreach ($alldownline as $listUnit) {
                                            echo "d.add(" . $listUnit['id'] .
                                                "," . $listUnit['upline_id'] .
                                                ",\"&nbsp;&nbsp;" . addslashes(isset($listUnit['name']) ? $listUnit['name'] : "") .
                                                " <em>(" . addslashes(strtolower((isset($listUnit['username']) ? $listUnit['username'] : ""))) .
                                                ")</em>\");\n";
                                        }
                                    } ?>
                            document.write(d);

                            //
                            -->
                        </script>
                    </div>
                <?php } else { ?>
                    <div>User not found.</div>
                <?php } ?></td>
        </tr>
    </table>
<?php } else { ?>
    <div>Empty</div>
<?php } ?>
