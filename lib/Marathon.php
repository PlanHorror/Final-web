<?php 
spl_autoload_register(function($class){
    include __DIR__ . '/' . $class . '.php';
});
class Marathon {
    private $db;
    public function __construct() {
        $this->db = new Database();
    }
    public function getAllMarathons() {
        $all = $this->db->read('marathon');
        // Count total
        foreach ($all as $key => $marathon) {
            $count = $this->db->count('marathon_id', $marathon['id'], 'participate');
            $all[$key]['total'] = $count;
        }
        return $all;
    }
    public function validateMarathon($data,$image){
        $errors = [];
        $today = date('Y-m-d');
        // Validate Date
        if ($data['date'] <= $today) {
            $errors['date'] = 'Date must be in the future';
        }
        // Validate Image
        if ($image['image']['size'] === 0) {
            $errors['image'] = 'Image is required';
        }
        // Validate size and type of image
        if ($image['image']['size'] > 1000000) {
            $errors['image'] = 'Image size must be less than 1MB';
        }
        if (!in_array($image['image']['type'], ['image/jpeg', 'image/png'])) {
            $errors['image'] = 'Image must be of type JPEG or PNG';
        }

        return $errors;
    }
    public function createMarathon($data,$image) {
        $errors = $this->validateMarathon($data,$image);
        if (count($errors) === 0) {
            try {
            $imagePath = 'image/' . 'marathon_images/' . $data['name']. '_' . $image['image']['name'];
            move_uploaded_file($image['image']['tmp_name'], __DIR__ . '/../' . $imagePath);
            $this->db->create( [
                'name' => $data['name'],
                'date' => $data['date'],
                'image' => $imagePath,
                'description' => $data['des']
            ], 'marathon');
            } catch (Exception $e) {
                $errors[] = 'Error creating marathon';
            }
            if (count($errors) === 0) {
                return;
            }
        }
        return $errors;
    }
    public function getParticipate($id){
        $participate = $this->db->readUseColumn('participate','marathon_id',$id);
        foreach ($participate as $key => $value) {
            $user = $this->db->find('id', $value['user_id'], 'user');
            $participate[$key]['user'] = $user;
        }
        return $participate;
    }
    public function findMarathon($id){
        return $this->db->find('id',$id,'marathon');
    }
    public function checkParticipateExist($id,$user_id){
        $par = $this->db->query('SELECT * FROM participate WHERE marathon_id = ' . $id . ' AND user_id = ' . $user_id);
        return count($par) > 0;
    }
    public function regParticipate($data){
        // If data contain hotel
        if (isset($data['hotel'])) {
            $this->db->create([
                'marathon_id' => $data['marathon_id'],
                'user_id' => $data['user_id'],
                'hotel' => $data['hotel']
            ], 'participate');
        } else {
            $this->db->create([
                'marathon_id' => $data['marathon_id'],
                'user_id' => $data['user_id']
            ], 'participate');
        }
        return;
    }
    public function updateParticipate($data){
        $marathon_id = $data['marathon_id'];
        unset($data['marathon_id']);
        var_dump($data);
        foreach ($data as $key => $value) {
            if ($key === 'entry_number') {
                foreach ($value as $key => $value1) {
                    $user_id = $key;
                    var_dump('UPDATE participate SET entry_number = "' . $value1 . '" WHERE marathon_id = ' . $marathon_id . ' AND user_id = ' . $user_id);
                    $this->db->query('UPDATE participate SET entry_number = "' . $value1 . '" WHERE marathon_id = ' . $marathon_id . ' AND user_id = ' . $user_id);
                }
            }
            if ($key === 'record') {
                foreach ($value as $key => $value1) {
                    $user_id = $key;
                    var_dump('UPDATE participate SET record = "' . $value1 . '" WHERE marathon_id = ' . $marathon_id . ' AND user_id = ' . $user_id);
                    $this->db->query('UPDATE participate SET record = "' . $value1 . '" WHERE marathon_id = ' . $marathon_id . ' AND user_id = ' . $user_id);
                }
            }
        }
        $this->updateStandings();
        $this->updateBestRecord();
        return;

        
    }
    public function updateStandings(){
        $all = $this->db->read('marathon');
        foreach ($all as $key => $marathon) {
            // Take all participants of the marathon
            $participate = $this->db->readUseColumn('participate', 'marathon_id', $marathon['id']);
            // Sort the participants by time but only not 00:00:00
            usort($participate, function($a, $b) {
                if ($a['record'] === '00:00:00') {
                    return 1;
                }
                if ($b['record'] === '00:00:00') {
                    return -1;
                }
                return $a['record'] <=> $b['record'];
            });
            $ket = 1;
            // Update the standings
            foreach ($participate as $key => $value) {
                if ($value['record'] === '00:00:00') {
                    $this->db->query('UPDATE participate SET standing = 0 WHERE marathon_id = ' . $marathon['id'] . ' AND user_id = ' . $value['user_id']);
                    continue;
                }
                $this->db->query('UPDATE participate SET standing = ' . $ket . ' WHERE marathon_id = ' . $marathon['id'] . ' AND user_id = ' . $value['user_id']);
                $ket++;
            }

        }
    }
    public function updateBestRecord(){
        $allUser = $this->db->read('user');
        foreach ($allUser as $key => $user) {
            $participate = $this->db->readUseColumn('participate', 'user_id', $user['id']);
            // Sort the participants by time but only not 00:00:00
            usort($participate, function($a, $b) {
                if ($a['record'] === '00:00:00') {
                    return 1;
                }
                if ($b['record'] === '00:00:00') {
                    return -1;
                }
                return $a['record'] <=> $b['record'];
            });
            $bestRecord = $participate[0]['record'];
            // Check best record is not 00:00:00
            if ($bestRecord === '00:00:00') {
                $bestRecord = null;
            }
            if ($bestRecord){
                $this->db->query('UPDATE user SET best_record = "' . $bestRecord . '" WHERE id = ' . $user['id']);
            }
        }
    }
}