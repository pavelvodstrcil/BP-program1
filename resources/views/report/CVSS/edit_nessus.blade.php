@extends ('layouts.app')


@section('content')


    <?php
    $permission = app('\App\Http\Controllers\permissionsController')->getPermission(Auth::user(), "report_CVSS");
    ?>

    @if ($permission)

    <h2 align="center">Editace řádku CVSS </h2>

    <?php
    try{
    $id = $row->id;
    }catch(Exception $e){
    echo 'Tato položka nebyla nalezena, prosím zkontrolujte zadání! (Zkontrolujte zpracování hodnot -> Reporty-> CVSS)';
    echo '<br>';
    echo '<a href=\'javascript:history.back(1);\'>Zpět!</a>';
    return 0;
    }
    ?>

    <h4 align="center">Verze CVSS: {{$row->version}}</h4>

    <form class="form-horizontal" action="update" method="POST">
        <input type="hidden" name="ID" value="{{$id}}" >
        {{ csrf_field() }}

        <h2 align="center"> BASE</h2>
<div class="form-group">
    <label class="control-label col-sm-2">FalsePositive</label>
    <div class="col-sm-10">
        <select class="form-control"  name="falsePositive">
            <option value="{{$row->falsePositive}}">{{$row->falsePositive}}</option>
            <option value="true" >1 - True</option>
            <option value="false" >0 - FALSE</option>
        </select>
    </div>
</div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Attack Vector (AV)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AV">
                    <option value="{{$row->AV}}">{{$row->AV}}</option>
                    <option value="N" >Network</option>
                    <option value="A" >Adjacent Network</option>
                    <option value="L" >Local</option>
                    <option value="P" >Psysical</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Attack Complexity (AC)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AC">
                    <option value="{{$row->AC}}">{{$row->AC}}</option>
                    <option value="L" >Low</option>
                    <option value="H" >High</option>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Privilege Required (AR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="PR">
                    <option value="{{$row->PR}}">{{$row->PR}}</option>
                    <option value="N" >None</option>
                    <option value="L" >Low</option>
                    <option value="H" >High</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >User Interaction (UI)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="UI">
                    <option value="{{$row->UI}}">{{$row->UI}}</option>
                    <option value="N" >None</option>
                    <option value="R" >Required</option>
                      </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Scope (S)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="S">
                    <option value="{{$row->S}}">{{$row->S}}</option>
                    <option value="U" >Unchanged</option>
                    <option value="C" >Changed</option>
                 </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Integrity (I)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="I">
                    <option value="{{$row->I}}">{{$row->I}}</option>
                    <option value="H" >High</option>
                    <option value="L" >Low</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Availability (A)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="A">
                    <option value="{{$row->A}}">{{$row->A}}</option>
                    <option value="H" >High</option>
                    <option value="L" >Low</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Confidentiality (C)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="C">
                    <option value="{{$row->C}}">{{$row->C}}</option>
                    <option value="H" >High</option>
                    <option value="L" >Low</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>

        <h2 align="center">TEMP metric</h2>


        <div class="form-group">
            <label class="control-label col-sm-2" >Exploit Code Maturity (E)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="E">
                    <option value="{{$row->E}}">{{$row->E}}</option>
                    <option value="" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="F" >Functional</option>
                    <option value="P" >Proof of Concept</option>
                    <option value="U" >Unproven</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Remediation level (RL)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="RL">
                    <option value="{{$row->RL}}">{{$row->RL}}</option>
                    <option value="" >Not Defined</option>
                    <option value="W" >Workaround</option>
                    <option value="T" >Temporary fix</option>
                    <option value="O" >Official fix</option>
                    <option value="U" >Unavailable</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Report Confidence (RC)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="RC">
                    <option value="{{$row->RC}}">{{$row->RC}}</option>
                    <option value="" >Not Defined</option>
                    <option value="C" >Confirmed</option>
                    <option value="R" >Reasonable</option>
                    <option value="U" >Unknown</option>
                    </select>
            </div>
        </div>

        <h2 align="center">Enviromental metric</h2>
        <div class="form-group">
            <label class="control-label col-sm-2" >Confidentiality reqv.(CR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="CR">
                    <option value="{{$row->CR}}">{{$row->CR}}</option>
                    <option value="" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Integrty reqv.(IR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="IR">
                    <option value="{{$row->IR}}">{{$row->IR}}</option>
                    <option value="" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Availability reqv.(AR)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="AR">
                    <option value="{{$row->AR}}">{{$row->AR}}</option>
                    <option value="" >Not Defined</option>
                    <option value="H" >High</option>
                    <option value="M" >Medium</option>
                    <option value="L" >Low</option>
                </select>
            </div>
        </div>





        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Attack Vector (MAV)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MAV">
                    <option value="{{$row->MAV}}">{{$row->MAV}}</option>
                    <option value="">Not defined</option>
                    <option value="N" >Network</option>
                    <option value="A" >Adjacent Network</option>
                    <option value="L" >Local</option>
                    <option value="P" >Psysical</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Attack Complexity (MAC)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MAC">
                    <option value="{{$row->MAC}}">{{$row->MAC}}</option>
                    <option value="">Not defined</option>
                    <option value="L" >Low</option>
                    <option value="H" >High</option>

                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Privilege Required (MAV)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MPR">
                    <option value="{{$row->MPR}}">{{$row->MPR}}</option>
                    <option value="">Not defined</option>
                    <option value="N" >None</option>
                    <option value="L" >Low</option>
                    <option value="H" >High</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed User Interaction (MUI)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MUI">
                    <option value="{{$row->MUI}}">{{$row->MUI}}</option>
                    <option value="">Not defined</option>
                    <option value="N" >None</option>
                    <option value="R" >Required</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Scope (MS)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MS">
                    <option value="{{$row->MS}}">{{$row->MS}}</option>
                    <option value="">Not defined</option>
                    <option value="U" >Unchanged</option>
                    <option value="C" >Changed</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Integrity (MI)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MI">
                    <option value="{{$row->MI}}">{{$row->MI}}</option>
                    <option value="">Not defined</option>
                    <option value="H" >High</option>
                    <option value="L" >Low</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Availability (MA)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MA">
                    <option value="{{$row->MA}}">{{$row->MA}}</option>
                    <option value="">Not defined</option>
                    <option value="H" >High</option>
                    <option value="L" >Low</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" >Modifed Confidentiality (MC)</label>
            <div class="col-sm-10">
                <select class="form-control"  name="MC">
                    <option value="{{$row->MC}}">{{$row->MC}}</option>
                    <option value="">Not defined</option>
                    <option value="H" >High</option>
                    <option value="L" >Low</option>
                    <option value="N" >None</option>
                </select>
            </div>
        </div>




     <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-default">Zpracovat</button>
            </div>
        </div>
    </form>



    @else
        <h1 align="center" > Na tuto operaci namáte oprávnění!  :-)</h1>
    @endif
@endsection
