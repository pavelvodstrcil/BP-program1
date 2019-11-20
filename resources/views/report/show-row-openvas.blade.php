@extends ('layouts.app')

@section('content')

    <?php

    $row = App\report_items_openvas::all()->where('id', $row);
    ?>
    @foreach($row as $item)

        <h2 align="center">Detailní výpis zvoleného řádku</h2>
        <h3><a class="btn btn-primary" href="{{ URL::previous() }}">Zpět do reportu</a></h3>

        <div class="panel panel-default">
            <div class="panel-heading">Host/IP</div>
            <div class="panel-body">
                {{$item->IP}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Host Name</div>
            <div class="panel-body">
                {{$item->Hostname}}
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Port + protokol</div>
            <div class="panel-body">
                {{$item->Port}} {{$item->PortProtocol}}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Severity</div>
            <div class="panel-body">
                {{$item->Severity}}
            </div>



        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Solution Type</div>
            <div class="panel-body">
                {{$item->SolutionType}}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">NVT Name</div>
            <div class="panel-body">
                {{$item->NVTName}}
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-heading">Summary</div>
            <div class="panel-body">
                {{$item->Summary}}
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Specific result</div>
            <div class="panel-body">
                {{$item->SpecificResult}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">NVTOID</div>
            <div class="panel-body">
                {{$item->NVTOID}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">CVE + CVSS report</div>
            <div class="panel-body">
                {{$item->CVEs}} {{$item->CVSS}}
            </div>
        </div>



        <div class="panel panel-default">
            <div class="panel-heading">Task Name</div>
            <div class="panel-body">
                {{$item->TaskName}}
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">Task ID</div>
            <div class="panel-body">
                {{$item->TaskID}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Impact</div>
            <div class="panel-body">
                {{$item->Impact}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Result ID</div>
            <div class="panel-body">
                {{$item->ResultID}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Impact</div>
            <div class="panel-body">
                {{$item->Impact}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Solution</div>
            <div class="panel-body">
                {{$item->Solution}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Affected Software / OS</div>
            <div class="panel-body">
                {{$item->AffectedSoftwareOS}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Vulnerability Detection Method</div>
            <div class="panel-body">
                {{$item->VulnerabilityDetectionMethod}}
            </div>
        </div>

            <div class="panel panel-default">
                <div class="panel-heading">Product detection result</div>
                <div class="panel-body">
                    {{$item->ProductDetectionResult}}
                </div>
            </div>


            <div class="panel panel-default">
                <div class="panel-heading">BIDs</div></div>
                <div class="panel-body">
                    {{$item->BIDs}}
                </div>
            </div>

        <div class="panel panel-default">
            <div class="panel-heading">Certs</div>
            <div class="panel-body">
                {{$item->CERTs}}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Other references</div>
            <div class="panel-body">
                {{$item->OtherReferences}}
            </div>
        </div>

    @endforeach
@endsection