@php
    $userRoles = isset($user) ? $user->roles->pluck('name')->all() : [];
    $userPermissions = isset($user) ? $user->getDirectPermissions()->pluck('name')->all() : [];
    $isSelf = isset($user) && $user->id === auth()->id();
    $isBloqueado = old('usr_blq', $user->usr_blq ?? \App\Models\User::USR_BLQ_DESBLOQUEADO) == \App\Models\User::USR_BLQ_BLOQUEADO;
@endphp

<div>
    <x-input-label for="name" :value="__('Nome')" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full" :value="old('name', $user->name ?? '')" required autofocus />
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="email" :value="__('E-mail')" />
    <x-text-input id="email" name="email" type="email" class="block mt-1 w-full" :value="old('email', $user->email ?? '')" required />
    <x-input-error :messages="$errors->get('email')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="password" :value="isset($user) ? __('Nova senha (deixe em branco para manter)') : __('Senha')" />
    <x-text-input id="password" name="password" type="password" class="block mt-1 w-full" :required="! isset($user)" />
    <x-input-error :messages="$errors->get('password')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label for="role" :value="__('Perfil')" />
    <select id="role" name="role" class="block mt-1 w-full border-gray-300 focus:border-santacasa focus:ring-santacasa rounded-md shadow-sm" required>
        <option value="">{{ __('Selecione...') }}</option>
        @foreach ($roles as $role)
            <option value="{{ $role->name }}" @selected(old('role', $userRoles[0] ?? '') === $role->name)>
                {{ ucfirst($role->name) }}
            </option>
        @endforeach
    </select>
    <x-input-error :messages="$errors->get('role')" class="mt-2" />
</div>

<div class="mt-4">
    <x-input-label :value="__('Permissões de módulo')" />
    <p class="text-sm text-gray-500 mb-2">{{ __('Aplicável apenas ao perfil Colaborador.') }}</p>
    <div class="space-y-2">
        @foreach ($permissions as $permission)
            <label class="flex items-center">
                <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                    class="rounded border-gray-300 text-santacasa shadow-sm focus:ring-santacasa"
                    @checked(in_array($permission->name, old('permissions', $userPermissions)))>
                <span class="ms-2 text-sm text-gray-700">{{ config('modules.'.$permission->name, $permission->name) }}</span>
            </label>
        @endforeach
    </div>
    <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
</div>

<div class="mt-4">
    <label class="flex items-center">
        <input type="hidden" name="usr_blq" value="{{ \App\Models\User::USR_BLQ_DESBLOQUEADO }}">
        <input type="checkbox" name="usr_blq" value="{{ \App\Models\User::USR_BLQ_BLOQUEADO }}"
            class="rounded border-gray-300 text-red-600 shadow-sm focus:ring-red-500"
            @checked($isBloqueado) @disabled($isSelf)>
        <span class="ms-2 text-sm text-gray-700">{{ __('Usuário bloqueado') }}</span>
    </label>
    @if ($isSelf)
        <p class="text-sm text-gray-500 mt-1">{{ __('Você não pode bloquear seu próprio usuário.') }}</p>
    @else
        <p class="text-sm text-gray-500 mt-1">{{ __('Um usuário bloqueado não consegue efetuar login.') }}</p>
    @endif
    <x-input-error :messages="$errors->get('usr_blq')" class="mt-2" />
</div>
