<?php

namespace bng\Models;

use bng\Models\BaseModel;

class AdminModel extends BaseModel
{
    // =======================================================
    public function get_all_clients()
    {
        // get all clients from all agents
        $this->db_connect();
        $results = $this->query(
            "SELECT " .
                "p.id, " .
                "AES_DECRYPT(p.name, '" . MYSQL_AES_KEY . "') name, " .
                "p.gender, " .
                "p.birthdate, " .
                "AES_DECRYPT(p.email, '" . MYSQL_AES_KEY . "') email, " .
                "AES_DECRYPT(p.phone, '" . MYSQL_AES_KEY . "') phone, " .
                "p.interests, " .
                "p.created_at, " .
                "AES_DECRYPT(a.name, '" . MYSQL_AES_KEY . "') agent " .
                "FROM persons p LEFT JOIN agents a " .
                "ON p.id_agent = a.id " .
                "WHERE p.deleted_at IS NULL " .
                "ORDER BY created_at DESC"
        );
        return $results;
    }

    // =======================================================
    public function get_agents_clients_stats()
    {
        // get totals from agent's clients
        $sql =
            "SELECT * FROM (" .
            "SELECT " .
            "p.id_agent, " .
            "AES_DECRYPT(a.name, 'Vduu47qL51hLn6bkYkY6NlO1nivsmdfD') agente, " .
            "COUNT(*) total_clientes " .
            "FROM persons p " .
            "LEFT JOIN agents a " .
            "ON a.id = p.id_agent " .
            "WHERE p.deleted_at IS NULL " .
            "GROUP BY id_agent ) a " .
            "ORDER BY total_clientes DESC";

        $this->db_connect();
        $results = $this->query($sql);
        return $results->results;
    }

    // =======================================================
    public function get_global_stats()
    {
        // get global stats from the database

        // total agents
        $this->db_connect();
        $results['total_agents'] = $this->query("SELECT count(*) value FROM agents")->results[0];

        // total active clients
        $results['total_clients'] = $this->query("SELECT count(*) value FROM persons WHERE deleted_at IS NULL")->results[0];

        // total inactive clients (deleted)
        $results['total_deleted_clients'] = $this->query("SELECT count(*) value FROM persons WHERE deleted_at IS NOT NULL")->results[0];

        // average number of clients per agent
        $results['average_clients_per_agent'] = $this->query(
            "SELECT (total_persons / total_agents) value FROM " .
            "( " .
            "SELECT " .
            "(SELECT COUNT(*) FROM persons) total_persons, " .
            "(SELECT COUNT(*) FROM agents WHERE PROFILE = 'agent') total_agents " .
            ") a "
        )->results[0];

        // younger client
        $results['younger_client'] = $this->query("SELECT TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) value FROM persons ORDER BY birthdate DESC LIMIT 1")->results[0];

        // oldest client
        $results['oldest_client'] = $this->query("SELECT TIMESTAMPDIFF(YEAR,birthdate,CURDATE()) value FROM persons ORDER BY birthdate ASC LIMIT 1")->results[0];

        // average age between all clients
        $results['average_age'] = $this->query("SELECT AVG(TIMESTAMPDIFF(YEAR,birthdate,CURDATE())) value FROM persons")->results[0];

        //percentage by gender - males
        $results['percentage_males'] = $this->query(
            "SELECT " .
            "CAST((total_males/total_clients) * 100 AS DECIMAL(5,2)) value " .
            "FROM " .
            "( " .
            "SELECT " .
            "(SELECT COUNT(*) FROM persons) total_clients, " .
            "(SELECT COUNT(*) FROM persons WHERE gender = 'm') total_males" .
            ") a ")->results[0];
        
        //percentage by gender - females
        $results['percentage_females'] = $this->query(
            "SELECT " .
            "CAST((total_females/total_clients) * 100 AS DECIMAL(5,2)) value " .
            "FROM " .
            "( " .
            "SELECT " .
            "(SELECT COUNT(*) FROM persons) total_clients, " .
            "(SELECT COUNT(*) FROM persons WHERE gender = 'f') total_females" .
            ") a")->results[0];
        
        return $results;
    }

    // =======================================================
    public function get_agents_for_management()
    {
        // get agents data to admin agents management

        $this->db_connect();
        $results = $this->query("
            SELECT
                id,
                AES_DECRYPT(name, '".MYSQL_AES_KEY."') `name`,
                profile,
                last_login,
                created_at,
                updated_at,
                deleted_at
            FROM agents
        ");

        return $results;
    }

    // =======================================================
    public function check_if_user_exists_with_same_name($name)
    {
        // check if there is a user with the $name
        $params = [
            ':name' => $name
        ];

        $this->db_connect();
        $results = $this->query(
            "SELECT id FROM agents " . 
            "WHERE AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "') = name"
        , $params);

        if($results->affected_rows == 0){
            return false;
        } else {
            return true;
        }
    }

    // =======================================================
    public function add_new_agent($data)
    {
        // add new agent to the database

        // generate purl
        $chars = 'abcdefghijkabcdefghijkabcdefghijkABCDEFGHIJKABCDEFGHIJKABCDEFGHIJK';
        $purl = substr(str_shuffle($chars), 0, 20);

        $params = [
            ':name' => $data['text_name'],
            ':profile' => $data['select_profile'],
            ':purl' => $purl
        ];

        $this->db_connect();
        $results = $this->non_query(
            "INSERT INTO agents VALUES(" . 
            "0, " . 
            "AES_ENCRYPT(:name, '" . MYSQL_AES_KEY . "'), " . 
            "NULL, " . 
            ":profile, " . 
            ":purl, " . 
            "NULL, " . 
            "NULL, " . 
            "NOW(), " . 
            "NULL, " . 
            "NULL)"
        , $params);
        
        if($results->affected_rows == 0){
            return [
                'status' => 'error'
            ];
        } else {
            return [
                'status' => 'success',
                'email' => $data['text_name'],
                'purl' => $purl
            ];
        }
    }
}
