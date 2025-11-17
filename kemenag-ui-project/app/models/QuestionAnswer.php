<?php

namespace App\Models;

use Core\Model;

/**
 * QuestionAnswer Model
 */
class QuestionAnswer extends Model
{
    protected $table = 'questions_answers';
    
    public function getPublished()
    {
        return $this->findBy('is_published', 1);
    }
    
    public function getByCategory($categoryId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE category_id = :category_id AND is_published = 1 ORDER BY created_at DESC";
        return $this->db->query($sql)->bind(':category_id', $categoryId)->fetchAll();
    }
    
    public function getBySlug($slug)
    {
        return $this->findOneBy('slug', $slug);
    }
    
    public function incrementViews($id)
    {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = :id";
        return $this->db->query($sql)->bind(':id', $id)->execute();
    }
    
    public function searchQA($term)
    {
        $sql = "SELECT * FROM {$this->table} WHERE MATCH(question, answer) AGAINST(:term IN NATURAL LANGUAGE MODE) AND is_published = 1";
        return $this->db->query($sql)->bind(':term', $term)->fetchAll();
    }
}
