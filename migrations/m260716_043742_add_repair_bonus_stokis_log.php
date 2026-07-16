<?php

use yii\db\Migration;

/**
 * Class m260716_043742_add_repair_bonus_stokis_log
 */
class m260716_043742_add_repair_bonus_stokis_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $tableExists = Yii::$app->db->createCommand("SHOW TABLES LIKE 'yr_repair_bonus_stokis_log'")->queryScalar();
        if ($tableExists) {
            echo "Table yr_repair_bonus_stokis_log already exists. Skipping create.\n";
            return true;
        }
        $this->createTable('{{%yr_repair_bonus_stokis_log}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(50)->notNull(),
            'ewallet_before' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'ewallet_after' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'added' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'deducted' => $this->decimal(10, 2)->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);
        $this->createIndex('idx_repair_bonus_stokis_log_username', '{{%yr_repair_bonus_stokis_log}}', 'username');
        $this->createIndex('idx_repair_bonus_stokis_log_created', '{{%yr_repair_bonus_stokis_log}}', 'created_at');
    }

    public function down()
    {
        $this->dropTable('{{%yr_repair_bonus_stokis_log}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m260716_043742_add_repair_bonus_stokis_log cannot be reverted.\n";

        return false;
    }
    */
}
