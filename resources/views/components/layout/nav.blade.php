<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="/">
                <img src="/images/logo.png" width="100" alt="Idea logo">
            </a>
        </div>
        <div class="flex gap-x-5 items-center">
            @guest
                <a href="/login">Sign In</a>
                <a href="/register" class="btn">Register</a>
            @else
                <form action="/logout" method="POST">
                    @csrf
                    <button class="btn">Log Out</button>
                </form>
            @endguest
        </div>
    </div>
</nav>
