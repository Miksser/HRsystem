<?php

class templateSelect extends DataBase
{
    function __construct()
    {
        $this->connect();
    }

    public function select_not_attached($table, $id)
    {
        if ($table == "vacancy") {
            $select = 'v_name, id';
            $select_id = 'vac_id';
            $where_id = 'can_id';
        } elseif ($table == "candidates") {
            $select = 'id, concat(surname, \' \', first_name, \' \', middle_name)sfm';
            $select_id = 'can_id';
            $where_id = 'vac_id';
        } else {

            return false;
        }

        $q = "SELECT $select FROM $table WHERE id NOT IN"
            . " (SELECT DISTINCT $select_id FROM comb_vac_cand WHERE $where_id = $id)";

        $query = pg_query($this->psqlconn, $q);

        if ($query) {
            $arr = $this->createArray($query);
            return $arr;
        } else {
            return false;
        }
    }

    /**
     * Request to get Join Table. Need Refactor.
     * @param $ arrRow
     * @param $arrFrom
     * @param $arrJoin
     * @param $arrOn
     * @param $arrAnd
     * @return bool|string
     *
     */

/*    public function select_join($arrRow, $arrFrom, $arrJoin, $arrOn, $arrAnd)
    {
        if (isset($arrRow) && isset($arrFrom) && isset($arrJoin) && isset($arrOn)) {

            $row = implode(", ", $arrRow);
            $from = implode(", ", $arrFrom);
            $join = implode(', ', $arrJoin);
            $on = implode(', ', $arrOn);

            $q = 'SELECT ' . $row . ' FROM ' . $from . ' JOIN ' . $join . ' on ' . $on;
        } else {
            return false;
        }
        if (isset($arrAnd)) {
            $and = implode($arrAnd);
            $q .= ' AND ' . $and;
        }

        $query = @pg_query($this->psqlconn, $q);

        if ($query) {
            $arr = $this->createArray($query);
            return $arr;
        } else {
            return false;
        }
    }*/
    public function select_join($table, $and)
    {
        if ($table == 'candidates') {

            $row = 'cvc.can_id, cvc.vac_id, v.v_name';
            $from = 'comb_vac_cand as cvc';
            $join = 'vacancy as v';
            $on = 'cvc.vac_id = v.id';

        } elseif($table == 'vacancy') {
            $row = 'cvc.can_id, cvc.vac_id, concat(c.surname, \' \', c.first_name, \' \', c.middle_name)sfm';
            $from = 'comb_vac_cand as cvc';
            $join = 'candidates as c';
            $on = 'cvc.can_id = c.id';
        } else {
            return false;
        }

        $q = 'SELECT ' . $row . ' FROM ' . $from . ' JOIN ' . $join . ' on ' . $on;


        if (isset($and)) {
            $q .= ' AND ' . $and;
        }

        $query = @pg_query($this->psqlconn, $q);

        if ($query) {
            $arr = $this->createArray($query);
            return $arr;
        } else {
            return false;
        }
    }
}