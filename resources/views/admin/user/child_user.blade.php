<div class="mgt-item-child">
    <div class="mgt-item">

        <div @if(count($child_user->allChildren)>0) class="mgt-item-parent" @else class="mgt-item-lastparent" @endif>
            <div class="person">
                <img src="{{$child_user->getImageUrlAttribute($child_user->id)}}" alt="{{$child_user->name}}"/>
                <p class="name">
                    @if(isset($child_user->latest_attendance_creator))
                        @if($child_user->latest_attendance_creator->status=='punch_in')
                            <span style="color:green;"><i class="fas fa-circle"></i></span>
                        @else 
                            <span style="color:red;"><i class="fas fa-circle"></i></span>
                        @endif
                    @else 
                        <span style="color:red;"><i class="fas fa-circle"></i></span>
                    @endif
                    {{$child_user->name}} / {{$child_user->position}}
                </p>
            </div>
        </div>

        @if(count($child_user->allChildren)>0)
        <div class="mgt-item-children">
            @foreach($child_user->allChildren as $child_user)
                @include('admin.user.child_user', ['child_user' => $child_user])
            @endforeach
        </div>
        @endif
    </div>
</div>