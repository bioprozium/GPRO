#include "CommandParser.h"
#include "Analyzing.h"
#include "outResult.h"
int main(int argc, char* argv[])
{
    CommandParser cp(argc, argv[1]);
    Analyzing anl(cp.prmt);
    outResult out(cp.prmt,anl.res);
    return 0;
}