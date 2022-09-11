




                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><input onclick="selectUser()" id="select_all" type="checkbox" style="height: 18px; width: 18px; margin-right: 10px;"><span>Select All</span></th>
                                        <th>NAME</th>
                                        <th>EMAIL</th>
                                        <th>PHONE</th>
                                        <th>CV</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @foreach ($users as $user)
                                    <tr>
                                        <th scope="row"><input onchange="assignQuizeToCandidate('user{{$user->id}}',{{$user->candidate_id}},{{$quizeId}})" id="user{{$user->id}}" class="user" type="checkbox" hidden {{in_array($user->candidate_id, $quizePermission) ? 'checked':''}}><label for="user{{$user->id}}"></label></th>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>{{ $user->phone }}</td>
                                        <td><a href="{{ $user->cv }}">{{ $user->cv }}</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>


