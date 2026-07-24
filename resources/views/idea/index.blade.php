@php use App\IdeaStatus; @endphp
<x-layout>
    <div>
        <header class="py-8 md:py-12">
            <h1 class="text-3xl font-bold">Ideas</h1>
            <p class="text-muted-foreground text-sm mt-2">Capture your thoughts. Make a plan.</p>
            <x-card
                x-data
                @click="$dispatch('open-modal', 'create-idea')"
                is="button"
                type="button"
                data-test="create-idea-button"
                class="mt-10 cursor-pointer h-32 w-full text-left">
                <p>What's the idea?</p>
            </x-card>
        </header>
        <div>
            <a href="/ideas"
               class="btn {{request()->has('status') ? 'btn-outlined' : ''}}">All</a>
            @foreach(IdeaStatus::cases() as $status)
                <a href="/ideas?status={{$status->value}}"
                   class="btn {{request('status') === $status->value ? '' : 'btn-outlined'}}">
                    {{$status->label()}}
                    <span class="text-xs pl-3">{{$statusCounts->get($status->value)}}</span>
                </a>
            @endforeach
        </div>
        <div class="mt-10 text-muted-foreground">
            <div class="grid md:grid-cols-2 gap-6">
                @forelse($ideas as $idea)
                    <x-card href="{{route('idea.show', $idea)}}">
                        <h3 class="text-foreground text-lg">{{$idea->title}}</h3>
                        <div class="mt-1">
                            <x-idea.status-label status="{{$idea->status}}">
                                {{$idea->status->label()}}
                            </x-idea.status-label>
                        </div>
                        <div class="mt-5 line-clamp-3">
                            {{$idea->description}}
                        </div>
                        <div class="mt-4">{{$idea->created_at->diffForHumans()}}</div>
                    </x-card>
                @empty
                    <x-card>
                        <p>No ideas at this time</p>
                    </x-card>
                @endforelse
            </div>
        </div>
        <x-modal name="create-idea" title="New Idea">
            <form
                x-data="{
                    status: 'pending',
                    newLink: '',
                    links: []
                }"
                action="/ideas"
                method="POST">
                @csrf
                <div class="space-y-6">
                    <x-form.field
                        label="Title"
                        name="title"
                        placeholder="Enter an idea for your title"
                        autofocus
                        required/>
                    <div class="space-y-2">
                        <label for="status" class="label">Status</label>
                        <div class="flex gap-x-3">
                            @foreach(IdeaStatus::cases() as $status)
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
                        autofocus/>
                    <div>
                        <fieldset class="space-y-3">
                            <legend class="label">Links</legend>
                            <template x-for="(link, index) in links">
                                <div class="flex gap-x-2 items-center">
                                    <input class="input" name="links[]" x-model="link">
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
                        <button type="submit" class="btn">Create</button>
                    </div>
                </div>
            </form>
        </x-modal>
    </div>
</x-layout>
