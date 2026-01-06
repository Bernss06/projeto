<?php
namespace backend\tests\Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    /**
     * Delete record from database
     *
     * @param string $table
     * @param array $criteria
     */
    public function deleteFromDatabase($table, $criteria)
    {
        $dbh = $this->getModule('Db')->dbh;
        
        $query = "DELETE FROM `" . $table . "`";
        $params = [];
        
        if (!empty($criteria)) {
            $conditions = [];
            foreach ($criteria as $k => $v) {
                $conditions[] = "`" . $k . "` = ?";
                $params[] = $v;
            }
            $query .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $stmt = $dbh->prepare($query);
        $stmt->execute($params);
    }
}
