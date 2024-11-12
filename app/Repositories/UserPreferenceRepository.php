<?php

namespace App\Repositories;

use App\Models\UserPreference;

class UserPreferenceRepository {

  public function save(int $user_id, array $data) {
    $preference = UserPreference::firstOrCreate(
      ['user_id' => $user_id],
      [
          'source_ids' => json_encode($data['source_ids'] ?? []),
          'category_ids' => json_encode($data['category_ids'] ?? []),
          'author_ids' => json_encode($data['author_ids'] ?? []),
      ]);
      return $preference;
  }
}