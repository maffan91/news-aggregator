<?php

namespace App\Repositories;

use App\Models\UserPreference;

class UserPreferenceRepository {

  public function save(int $user_id, array $data) {
    $preference = UserPreference::updateOrCreate(
      ['user_id' => $user_id],
      [
          'source_ids' => $data['source_ids'] ?? [],
          'category_ids' => $data['category_ids'] ?? [],
          'author_ids' => $data['author_ids'] ?? [],
      ]);
      return $preference;
  }
}