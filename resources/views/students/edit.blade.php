<h2>Edit Student</h2>

<form method="POST" action="{{ route('students.update', $student->id) }}">
    @csrf

    Name: <input type="text" name="name" value="{{ $student->name }}"><br><br>
    Email: <input type="text" name="email" value="{{ $student->email }}"><br><br>
    Phone: <input type="text" name="phone" value="{{ $student->phone }}"><br><br>

    <button type="submit">Update</button>
</form>