<div>
    <x-input-label for="name" :value="__('Nome (chave)')" />
    <x-text-input id="name" name="name" type="text" class="block mt-1 w-full font-mono" :value="old('name', $permission->name ?? '')" required autofocus placeholder="ex: relatorios-financeiros" />
    <p class="text-sm text-gray-500 mt-1">{{ __('Use apenas letras, números, hífen e underline. Esta chave é usada para liberar o acesso de um colaborador a um módulo.') }}</p>
    <x-input-error :messages="$errors->get('name')" class="mt-2" />
</div>
