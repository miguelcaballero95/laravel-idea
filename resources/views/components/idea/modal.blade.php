@props([
    'idea' => new App\Models\Idea()
])

<x-modal
    name="{{ $idea->exists ? 'edit-idea' : 'create-idea' }}"
    title="{{$idea->exists ? 'Edit Idea' : 'New Idea'}}">
    <form
        x-data="{
                    status: @js(old('status', $idea->status->value)),
                    newLink: '',
                    links: @js(old('links', $idea->links)),
                    newStep: '',
                    steps: @js(old('steps', $idea->steps->map->only(['id', 'description', 'completed']))),
                }"
        action="{{$idea->exists ? route('idea.update', $idea) : route('idea.store')}}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @if($idea->exists)
            @method('PATCH')
        @endif
        <div class="space-y-6">
            <x-form.field
                label="Title"
                name="title"
                placeholder="Enter an idea for your title"
                autofocus
                :value="$idea->title"
                required/>
            <div class="space-y-2">
                <label for="status" class="label">Status</label>
                <div class="flex gap-x-3">
                    @foreach(App\IdeaStatus::cases() as $status)
                        <button
                            type="button"
                            @click="status = @js($status->value)"
                            class="btn flex-1 h-10"
                            data-test="button-status-{{$status->value}}"
                            :class="status === @js($status->value) ? '' : 'btn-outlined'">
                            {{$status->label()}}
                        </button>
                    @endforeach
                    <x-form.error name="status"/>
                    <input type="hidden" name="status" :value="status">
                </div>
            </div>
            <x-form.field
                label="Description"
                name="description"
                type="textarea"
                placeholder="Describe your idea..."
                :value="$idea->description"
                autofocus
            />
            <div class="space-y-2">
                <label for="image" class="label">Featured Image</label>
                @if($idea->image_path)
                    <div class="space-y-2">
                        <img
                            src="{{asset('storage/' . $idea->image_path)}}"
                            alt="{{$idea->title}}"
                            class="w-full h-48 object-cover rounded-lg"
                        />
                        <button
                            form="delete-image-form"
                            class="btn btn-outlined h-10 w-full">
                            Remove Image
                        </button>
                    </div>
                @endif
                <input type="file" name="image" accept="image/*"/>
                <x-form.error name="image"/>
            </div>
            <div>
                <fieldset class="space-y-3">
                    <legend class="label">Actionable Steps</legend>
                    <template x-for="(step, index) in steps" :key="step.id || index">
                        <div class="flex gap-x-2 items-center">
                            <input
                                class="input"
                                :name="`steps[${index}][description]`"
                                x-model="step.description"
                                readonly
                            />
                            <input
                                class="hidden"
                                :name="`steps[${index}][completed]`"
                                x-model="step.completed ? '1' : '0'"
                                readonly
                            />
                            <button
                                type="button"
                                class="form-muted-icon"
                                @click="steps.splice(index, 1)"
                                aria-label="Remove step">
                                <x-icons.close/>
                            </button>
                        </div>
                    </template>
                    <div class="flex gap-x-2 items-center">
                        <input
                            x-model="newStep"
                            type="text"
                            id="new-step"
                            data-test="new-step"
                            placeholder="What needs to be done?"
                            class="input flex-1"
                            spellcheck="false">
                        <button
                            type="button"
                            @click="steps.push({description: newStep.trim(), completed: false}); newStep = '';"
                            data-test="submit-new-step-button"
                            :disabled="newStep.trim().length <= 0"
                            class="form-muted-icon"
                            aria-label="Add a new link">
                            <x-icons.close class="rotate-45"/>
                        </button>
                    </div>
                </fieldset>
            </div>
            <div>
                <fieldset class="space-y-3">
                    <legend class="label">Links</legend>
                    <template x-for="(link, index) in links">
                        <div class="flex gap-x-2 items-center">
                            <input class="input" name="links[]" x-model="link" readonly>
                            <button
                                type="button"
                                class="form-muted-icon"
                                @click="links.splice(index, 1)"
                                aria-label="Remove link">
                                <x-icons.close/>
                            </button>
                        </div>
                    </template>
                    <div class="flex gap-x-2 items-center">
                        <input
                            x-model="newLink"
                            type="text"
                            id="new-link"
                            data-test="new-link"
                            placeholder="http://example.com"
                            autocomplete="url"
                            class="input flex-1"
                            spellcheck="false">
                        <button
                            @click="links.push(newLink.trim()); newLink = '';"
                            :disabled="newLink.trim().length <= 0"
                            type="button"
                            data-test="submit-new-link-button"
                            class="form-muted-icon"
                            aria-label="Add a new link">
                            <x-icons.close class="rotate-45"/>
                        </button>
                    </div>
                </fieldset>
            </div>
            <div class="flex justify-end gap-x-5">
                <button
                    @click="$dispatch('close-modal')"
                    type="button">
                    Cancel
                </button>
                <button
                    type="submit"
                    data-test="submit-create-idea-button"
                    class="btn">
                    {{$idea->exists ? 'Edit' : 'Create'}}
                </button>
            </div>
        </div>
    </form>
    @if($idea->image_path)
        <form id="delete-image-form" action="{{route('idea.image.destroy', $idea)}}" method="POST">
            @csrf
            @method('DELETE')
        </form>
    @endif
</x-modal>
