<?php
    namespace Tests;

    use Kairnial\Common\Models\ExternalUser;
    use stdClass;

    class MockUser extends ExternalUser
    {
        public static function CreateMockUser(string $identifier, array $scopes): static
        {
            $jwt = new stdClass();
            $jwt->scopes = $scopes;
            $jwt->sub = $identifier;
            $user = self::CreateFromJWT($jwt);

            return $user->SetAccessToken($jwt);
        }
    }
