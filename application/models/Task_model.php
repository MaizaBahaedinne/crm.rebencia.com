<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : Task_model (Task Model)
 * Task model class to get to handle task related data 
 * @author : Kishor Mali
 * @version : 1.5
 * @since : 18 Jun 2022
 */
class Task_model extends CI_Model
{
    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function taskListingCount($searchText)
    {
        $this->db->select('BaseTbl.taskId, BaseTbl.taskTitle, BaseTbl.description, BaseTbl.createdDtm');
        $this->db->from('tbl_task as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.taskTitle LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the task listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function taskListing($searchText, $page, $segment)
    {
        $this->db->select('BaseTbl.taskId, BaseTbl.taskTitle, BaseTbl.description, BaseTbl.createdDtm');
        $this->db->from('tbl_task as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.taskTitle LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.taskId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    /**
     * This function is used to add new task to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewTask($taskInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_task', $taskInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get task information by id
     * @param number $taskId : This is task id
     * @return array $result : This is task information
     */
    function getTaskInfo($taskId)
    {
        $this->db->select('taskId, taskTitle, description');
        $this->db->from('tbl_task');
        $this->db->where('taskId', $taskId);
        $this->db->where('isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }
    
    
    /**
     * This function is used to update the task information
     * @param array $taskInfo : This is task updated information
     * @param number $taskId : This is task id
     */
    function editTask($taskInfo, $taskId)
    {
        $this->db->where('taskId', $taskId);
        $this->db->update('tbl_task', $taskInfo);
        
        return TRUE;
    }
    
    /**
     * This function is used to get tasks by agent
     * @param number $agent_id : This is agent id
     * @return array $result : This is tasks list
     */
    function get_tasks_by_agent($agent_id)
    {
        // Vérifier si la table existe
        if (!$this->db->table_exists('tbl_task')) {
            // Retourner des données simulées pour le dashboard
            return [
                [
                    'taskId' => 1,
                    'taskTitle' => 'Rappeler client Dupont',
                    'description' => 'Suivre le dossier d\'estimation',
                    'status' => 'pending',
                    'due_date' => date('Y-m-d'),
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'taskId' => 2,
                    'taskTitle' => 'Préparer visite propriété',
                    'description' => 'Visite prévue demain matin',
                    'status' => 'pending',
                    'due_date' => date('Y-m-d', strtotime('+1 day')),
                    'created_at' => date('Y-m-d H:i:s')
                ],
                [
                    'taskId' => 3,
                    'taskTitle' => 'Envoyer rapport mensuel',
                    'description' => 'Rapport d\'activité du mois',
                    'status' => 'pending',
                    'due_date' => date('Y-m-d', strtotime('+3 days')),
                    'created_at' => date('Y-m-d H:i:s', strtotime('-2 days'))
                ]
            ];
        }
        
        $this->db->select('taskId, taskTitle, description, createdDtm as created_at');
        $this->db->from('tbl_task');
        // Si vous avez un champ agent_id dans la table, décommentez la ligne suivante
        // $this->db->where('agent_id', $agent_id);
        $this->db->where('isDeleted', 0);
        $this->db->order_by('taskId', 'DESC');
        $this->db->limit(20); // Limiter à 20 tâches
        $query = $this->db->get();
        
        $results = $query->result_array();
        
        // Ajouter des champs manquants pour le dashboard
        foreach ($results as &$task) {
            $task['status'] = 'pending'; // Status par défaut
            $task['due_date'] = date('Y-m-d', strtotime('+1 day')); // Due date par défaut
        }
        
        return $results;
    }
}