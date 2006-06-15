<?php
require_once("docutil.php");
page_head("XML formats");
echo "
BOINC uses XML to describe various entities.
These XML elements appear in:
<ul>
<li> <a href=database.php>The BOINC database</a>
<li> <a href=protocol.php>Scheduler request and reply messages</a>
<li> <a href=client_files.php>The client state file</a>
<li> <a href=gui_rpc.php>GUI RPCs</a>
</ul>
Generally, these XML elements are generated by BOINC.
Some of these XML elements are described here
(not all fields are present in all contexts).
<a name=file></a>
<h3>Files</h3>
<p>
A file is described by an XML element of the form 
".html_text("
<file_info>
    <name>foobar</name> *
    <url>http://a.b.c/foobar</url>
    <url>http://x.y.z/foobar</url>
    ...
    <md5_cksum>123123123123</md5_cksum>
    <nbytes>134423</nbytes>
    <max_nbytes>200000</max_nbytes> *
    <status>1</status>
    [ <generated_locally/> ]
    [ <executable/> ]
    [ <upload_when_present/> ]
    [ <sticky/> * ]
    [ <signature_required/> ]
    [ <no_delete/> * ]
    [ <report_on_rpc/> * ]
</file_info>
")."
The elements marked with an asterisk are specified by the project;
the others are generated automatically by BOINC.
The elements are as follows:
";
list_start();
list_item(
    "name",
    "The file's name, which must be unique within the project.
    If you want to use participant hosts on which
    filenames are case-insensitive (e.g. Windows)
    this uniqueness is case-insensitive."
);
list_item("url",
    "a URL where the file is (or will be) located on a data server."
);
list_item("md5_cksum", "The MD5 checksum of the file."
);
list_item("nbytes",
    "the size of the file in bytes."
);
list_item("max_nbytes",
    "For output files,
	the maximum allowable size of the file in bytes
	(as a double; may be greater than 2^32).
    This is used to prevent flooding data servers with bogus data."
);
list_item("status",
    "On the client: 0 if the file is not present;
    1 if the file is present; a negative error code if there was a
    problem in downloading or generating the file."
);
list_item("generated_locally",
    "If present, indicates that the file will be generated on the client,
	rather than downloaded."
);
list_item("executable",
    "If present, indicates that the file protections should be set to allow
    execution."
);
list_item("upload_when_present",
    "If present, indicates that the file should be uploaded
     when the application finishes.
     The file is uploaded even if the application doesn't
     finish successfully.
     API functions are available for
     <a href=int_upload.php>uploading files prior to
     finishing computation</a>.
");
list_item("sticky",
    "If present, indicates that the file should be retained
    on the client after its initial use."
);
list_item("signature_required",
    "If present, indicates that the file should be verified with an
    RSA signature.
    This generally only applies to executable files."
);
list_item("no_delete",
    "If present for an input (workunit) file,
    indicates that the file should not be removed from the data server's
    download directory when the workunit is completed.
    Use this if a file is used by more than one workunit,
	or will be used by future workunits.
    <p>
    If present for an output (result) file,
    indicates that the file should not be removed from the data server's upload
    directory,
	even when the corresponding workunit is completed and assimilated.
    Use with caution - this may cause your upload directory to overflow."
);
list_item("report_on_rpc",
    "Include a description of this file in scheduler RPC requests,
    so that the scheduler may send appropriate work
    using <a href=sched_locality.php>locality scheduling</a>."
);
list_end();
echo "
<a name=file_ref></a>
<h3>File references</h3> 
<p>
Files may be associated with <a href=work.php>workunits</a>,
<a href=result.php>results</a> and
<a href=app.php>application versions</a>.
Each such association is represented by an XML element of the form 
".html_text("
<file_ref>
    <file_name>foobar</file_name>
    [ <open_name>input</open_name> ]
    [ <main_program/> ]
    [ <copy_file/> ]
</file_ref>
")."
The elements are as follows: 
";
list_start();
list_item("file_name", "Specifies a file.");
list_item("open_name",
    "The name by which the application will refer to the file.
    Applications access files using
    <a href=api.php>the following functions</a>:
    <pre>
        char physical_name[256];
        boinc_resolve_filename(\"input\", physical_name, 256);
        fopen(physical_name, \"r\")
    </pre>
    In this example, open_name is 'input'.
    It is mapped, at runtime, to a path that includes
    the filename ('foobar' in the example above).
");
list_item("main_program",
    "Relevant only for files associated with application versions.
    It indicates that this file is the application's main program.
");
list_item("copy_file"
    "
    Use this when an application doesn't use
    boinc_resolve_filename() to make logical to physical filenames
    (for example, executables without source code).
    If present on an input file,
    copy the file to the slot directory before starting application.
    If present on an output file,
    move the file from the slot directory to the project directory
    after the application."
);
list_end();

echo "
<a name=app_version></a>
<h3>App versions</h3>

Each entry in the app version table includes an XML document describing the
files that make up the application version: 
".html_text("
<file_info>
   ... 
</file_info>
[ ... ]
<app_version>
    <app_name>foobar</app_name>
    <version_num>4</version_num>
    <file_ref>
        <file_name>program_1</file_name>
        <main_program/>
    </file_ref>
    <file_ref>
        <file_name>library_12</file_name>
    </file_ref>
</app_version>")."
";
page_tail();
?>
