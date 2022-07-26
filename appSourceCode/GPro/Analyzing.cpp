#include "Analyzing.h"
#include <iostream>
#include <fstream>
#include <algorithm>
#include <string>
#include <map>

Analyzing::Analyzing(CommandParser::Parameters p)
{
	try
	{
		if (p.operation == "calc")
		{
			std::ifstream file(p.pc.fileSource, std::ios::in | std::ios::binary);
			std::string line{ "" };
			std::string sequence{ "" };
			infoBlockInitialize(p.pc.action);
			while (std::getline(file, line))
			{
				line.erase(std::remove(line.begin(), line.end(), '\n'), line.end());
				line.erase(std::remove(line.begin(), line.end(), '\r'), line.end());
				if (line[0] == '>')
				{
					//header
					if (sequence != "")
					{
						Analyze(p.pc.action, sequence);
						sequence = "";
					}
				}
				if (line[0] != '>')
				{
					sequence += line;
				}
			}
			Analyze(p.pc.action, sequence);
			Statistics(p.pc.action);
			resultPreparation(p.pc.action);
			//for (std::map<char, unsigned int>::iterator it = aaContent.begin(); it != aaContent.end(); ++it)
			//{
				//std::cout << it->first << ": " << it->second << '\n';
			//}
			file.close();

		}
		else if (p.operation == "prep")
		{
			// FNA + GTF preparation
		}
		else
		{
			e = "Error";
			throw(e);
		}
	}
	catch (std::string err)
	{
		std::cerr << err;
		std::cout << "Operation type is invalid!!! Check your commandLine";
	}
}

void Analyzing::Analyze(std::string action, std::string sequence)
{
	try
	{
		if (action == "cdnocc")
		{
			codonOccurrence(sequence);
		}
		else if (action == "cdnpos")
		{
			codonPosition(sequence);
		}
		else if (action == "len")
		{
			length(sequence);
		}
		else if (action == "gc")
		{
			gcContent(sequence);
		}
		else if (action == "cpg")
		{
			cpgIsland(sequence);
		}
		else if (action == "aa")
		{
			aminoAcidContent(sequence);
		}
		else if (action == "aapos")
		{
			aminoAcidPosition(sequence);
		}
		else
		{
			e = "Error";
			throw(e);
		}
	}
	catch (std::string err)
	{
		std::cerr << err;
		std::cout << "Something with calculation!";
	}
}

/*-------------------------------------------Calculation------------------------------------------*/

void Analyzing::codonOccurrence(std::string s)
{
	size_t cdnCount = (s.size())/3;
	
	for (size_t i = 0; i < s.size(); i = i + 3)
	{
		if ((i + 3) > s.size())
			break;
		std::string codon{};
		codon = s.substr(i, 3);
		std::transform(codon.begin(), codon.end(), codon.begin(), ::toupper);
		std::map<std::string,  double>::iterator it = cdnOcc.find(codon);
		if (it != cdnOcc.end())
			it->second++;
	}
	std::map<std::string,  double>::iterator it = cdnOcc.find("cdnCount");
	if (it != cdnOcc.end())
		it->second = it->second + cdnCount;
	
}
void Analyzing::codonPosition(std::string s)
{
	size_t pos = 0;
	for (size_t i = 0; i < s.size(); i = i + 3)
	{
		if (pos == 50)
			break;
		//initCodonPosindex(pos);
		if ((i + 3) > s.size())
			break;
		std::string codon{};
		codon = s.substr(i, 3);
		if (codonPos.find(codon) != codonPos.end())
		{
			codonPos[codon][pos]++;
		}
		for (std::map<std::string, std::map<size_t, size_t>>::iterator i = codonPos.begin(); i != codonPos.end(); ++i)
		{
			if (i->first != codon)
			{
				if (i->second.find(pos) == i->second.end())
					i->second[pos] = 0;
			}
		}
		pos++;
	}
}
void Analyzing::length(std::string s)
{
	size_t category = s.size() / 500;			//0-499;500-999;1000-1499
	std::map<size_t, int>::iterator it = seqLength.find(category);
	if (it != seqLength.end())
	{
		it->second++;
	}
	else
	{
		seqLength.emplace(category, 1);
	}

}
void Analyzing::gcContent(std::string s)
{
	size_t sequenceSize = s.size();
	float gc{ 0 };
	for (char& c : s)
	{
		if (c == 'g' || c == 'G')
		{
			gc++;
		}
		if (c == 'c' || c == 'C')
		{
			gc++;
		}
	}
	float concent = round((gc * 100) / sequenceSize);
	int conc = static_cast<int>(concent);
	concentration[conc]++;
}
void Analyzing::cpgIsland(std::string s)
{
	size_t sequenceSize = s.size();
	std::transform(s.begin(), s.end(), s.begin(), ::toupper);
	float cpg{ 0 };
	for (size_t i = 0; i < sequenceSize; i++)
	{
		if (s[i] == 'C' && s[i + 1] == 'G')
			cpg++;
	}
	float concent = round((cpg * 100) / sequenceSize);
	int conc = static_cast<int>(concent);
	concentration[conc]++;
}
void Analyzing::aminoAcidContent(std::string s)
{
	const char* c = s.c_str();
	size_t length = strlen(c);
	for (int i = 0; i < length; i+=3)
	{
		int location = get_seq_num(&c[i], 3);
		char amino = aa[location];
		aaContent[amino]++;
	}
}
void Analyzing::aminoAcidPosition(std::string s)
{

}
void Analyzing::kMers(std::string s)
{

}
/*-------------------------------------------Statistics-------------------------------------------*/

void Analyzing::Statistics(std::string action)
{
	if (action == "cdnocc")
	{
		codonOccurrenceStatistics();
	}
}
void Analyzing::codonOccurrenceStatistics()
{
	/*std::map<std::string, double>::iterator iter = cdnOcc.find("cdnCount");
	for (std::map<std::string, double>::iterator it = cdnOcc.begin(); it != cdnOcc.end(); ++it)
	{
		if (it->first != "cdnCount")
		{
			it->second = (it->second*100) / iter->second;
		}
	}*/
}

/*-------------------------------------------TOOLS---------------------------------------------------*/


void Analyzing::infoBlockInitialize(std::string action)
{
	if (action == "cdnocc" || action == "cdnusage")
		cdnOcc = initializeCodonMap(cdnOcc);
	if (action == "cdnoccog")
		cdnOcc = initializeCodonMap(cdnOcc);
	if (action == "cdnpos")
		initializeCodonPosMap(codonPos,0);
	if (action == "gc" || action == "cpg")
		initConcentrationMap(concentration);
	if (action == "aa")
		initAminoAcidMap(aaContent,aa);
}
std::map<std::string, double> Analyzing::initializeCodonMap(std::map<std::string, double> cdn)
{
	cdn.emplace("AAA", 0);
	cdn.emplace("AAC", 0);
	cdn.emplace("AAG", 0);
	cdn.emplace("AAT", 0);
	cdn.emplace("ACA", 0);
	cdn.emplace("ACC", 0);
	cdn.emplace("ACG", 0);
	cdn.emplace("ACT", 0);
	cdn.emplace("AGA", 0);
	cdn.emplace("AGC", 0);
	cdn.emplace("AGG", 0);
	cdn.emplace("AGT", 0);
	cdn.emplace("ATA", 0);
	cdn.emplace("ATC", 0);
	cdn.emplace("ATG", 0);
	cdn.emplace("ATT", 0);
	cdn.emplace("CAA", 0);
	cdn.emplace("CAC", 0);
	cdn.emplace("CAG", 0);
	cdn.emplace("CAT", 0);
	cdn.emplace("CCA", 0);
	cdn.emplace("CCC", 0);
	cdn.emplace("CCG", 0);
	cdn.emplace("CCT", 0);
	cdn.emplace("CGA", 0);
	cdn.emplace("CGC", 0);
	cdn.emplace("CGG", 0);
	cdn.emplace("CGT", 0);
	cdn.emplace("CTA", 0);
	cdn.emplace("CTC", 0);
	cdn.emplace("CTG", 0);
	cdn.emplace("CTT", 0);
	cdn.emplace("GAA", 0);
	cdn.emplace("GAC", 0);
	cdn.emplace("GAG", 0);
	cdn.emplace("GAT", 0);
	cdn.emplace("GCA", 0);
	cdn.emplace("GCC", 0);
	cdn.emplace("GCG", 0);
	cdn.emplace("GCT", 0);
	cdn.emplace("GGA", 0);
	cdn.emplace("GGC", 0);
	cdn.emplace("GGG", 0);
	cdn.emplace("GGT", 0);
	cdn.emplace("GTA", 0);
	cdn.emplace("GTC", 0);
	cdn.emplace("GTG", 0);
	cdn.emplace("GTT", 0);
	cdn.emplace("TAA", 0);
	cdn.emplace("TAC", 0);
	cdn.emplace("TAG", 0);
	cdn.emplace("TAT", 0);
	cdn.emplace("TCA", 0);
	cdn.emplace("TCC", 0);
	cdn.emplace("TCG", 0);
	cdn.emplace("TCT", 0);
	cdn.emplace("TGA", 0);
	cdn.emplace("TGC", 0);
	cdn.emplace("TGG", 0);
	cdn.emplace("TGT", 0);
	cdn.emplace("TTA", 0);
	cdn.emplace("TTC", 0);
	cdn.emplace("TTG", 0);
	cdn.emplace("TTT", 0);
	cdn.emplace("cdnCount", 0);
	return cdn;
}

void Analyzing::initializeCodonPosMap(std::map<std::string, std::map<size_t, size_t>>& cdnPos, size_t index)
{
	cdnPos.emplace(std::make_pair("AAA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AAC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AAG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AAT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ACA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ACC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ACG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ACT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AGA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AGC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AGG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("AGT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ATA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ATC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ATG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("ATT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CAA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CAC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CAG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CAT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CCA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CCC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CCG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CCT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CGA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CGC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CGG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CGT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CTA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CTC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CTG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("CTT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GAA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GAC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GAG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GAT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GCA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GCC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GCG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GCT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GGA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GGC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GGG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GGT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GTA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GTC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GTG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("GTT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TAA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TAC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TAG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TAT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TCA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TCC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TCG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TCT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TGA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TGC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TGG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TGT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TTA", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TTC", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TTG", std::map< size_t, size_t>({std::make_pair(index, 0)})));
	cdnPos.emplace(std::make_pair("TTT", std::map< size_t, size_t>({std::make_pair(index, 0)})));
}

void Analyzing::initCodonPosindex(size_t index)
{
	codonPos["TAA"][index] = 0;
	codonPos["AAA"][index] = 0;
	codonPos["AAC"][index] = 0;
	codonPos["AAG"][index] = 0;
	codonPos["AAT"][index] = 0;
	codonPos["ACA"][index] = 0;
	codonPos["ACC"][index] = 0;
	codonPos["ACG"][index] = 0;
	codonPos["ACT"][index] = 0;
	codonPos["AGA"][index] = 0;
	codonPos["AGC"][index] = 0;
	codonPos["AGG"][index] = 0;
	codonPos["AGT"][index] = 0;
	codonPos["ATA"][index] = 0;
	codonPos["ATC"][index] = 0;
	codonPos["ATG"][index] = 0;
	codonPos["ATT"][index] = 0;
	codonPos["CAA"][index] = 0;
	codonPos["CAC"][index] = 0;
	codonPos["CAG"][index] = 0;
	codonPos["CAT"][index] = 0;
	codonPos["CCA"][index] = 0;
	codonPos["CCC"][index] = 0;
	codonPos["CCG"][index] = 0;
	codonPos["CCT"][index] = 0;
	codonPos["CGA"][index] = 0;
	codonPos["CGC"][index] = 0;
	codonPos["CGG"][index] = 0;
	codonPos["CGT"][index] = 0;
	codonPos["CTA"][index] = 0;
	codonPos["CTC"][index] = 0;
	codonPos["CTG"][index] = 0;
	codonPos["CTT"][index] = 0;
	codonPos["GAA"][index] = 0;
	codonPos["GAC"][index] = 0;
	codonPos["GAG"][index] = 0;
	codonPos["GAT"][index] = 0;
	codonPos["GCA"][index] = 0;
	codonPos["GCC"][index] = 0;
	codonPos["GCG"][index] = 0;
	codonPos["GCT"][index] = 0;
	codonPos["GGA"][index] = 0;
	codonPos["GGC"][index] = 0;
	codonPos["GGG"][index] = 0;
	codonPos["GGT"][index] = 0;
	codonPos["GTA"][index] = 0;
	codonPos["GTC"][index] = 0;
	codonPos["GTG"][index] = 0;
	codonPos["GTT"][index] = 0;
	codonPos["TAA"][index] = 0;
	codonPos["TAC"][index] = 0;
	codonPos["TAG"][index] = 0;
	codonPos["TAT"][index] = 0;
	codonPos["TCA"][index] = 0;
	codonPos["TCC"][index] = 0;
	codonPos["TCG"][index] = 0;
	codonPos["TCT"][index] = 0;
	codonPos["TGA"][index] = 0;
	codonPos["TGC"][index] = 0;
	codonPos["TGG"][index] = 0;
	codonPos["TGT"][index] = 0;
	codonPos["TTA"][index] = 0;
	codonPos["TTC"][index] = 0;
	codonPos["TTG"][index] = 0;
	codonPos["TTT"][index] = 0;
}

void Analyzing::initConcentrationMap(std::map<int, unsigned int>& conc)
{
	for (int i = 0; i < 101; i++)
	{
		conc.emplace(i, 0);
	}

}
void Analyzing::initAminoAcidMap(std::map<char, unsigned int>& aaC, char(&aa)[64])
{
	aa[0] = 'K';aa[1] = 'N';aa[2] = 'K';aa[3] = 'N';
	aa[4] = 'T';aa[5] = 'T';aa[6] = 'T';aa[7] = 'T';
	aa[8] = 'R';aa[9] = 'S';aa[10] = 'R';aa[11] = 'S';
	aa[12] = 'M';aa[13] = 'I';aa[14] = 'M';aa[15] = 'I';
	aa[16] = 'Q';aa[17] = 'H';aa[18] = 'Q';aa[19] = 'H';
	aa[20] = 'P';aa[21] = 'P';aa[22] = 'P';aa[23] = 'P';
	aa[24] = 'R';aa[25] = 'R';aa[26] = 'R';aa[27] = 'R';
	aa[28] = 'L';aa[29] = 'L';aa[30] = 'L';aa[31] = 'L';
	aa[32] = 'E';aa[33] = 'D';aa[34] = 'E';aa[35] = 'D';
	aa[36] = 'A';aa[37] = 'A';aa[38] = 'A';aa[39] = 'A';
	aa[40] = 'G';aa[41] = 'G';aa[42] = 'G';aa[43] = 'G';
	aa[44] = 'V';aa[45] = 'V';aa[46] = 'V';aa[47] = 'V';
	aa[48] = 's';aa[49] = 'Y';aa[50] = 's';aa[51] = 'Y';
	aa[52] = 'S';aa[53] = 'S';aa[54] = 'S';aa[55] = 'S';
	aa[56] = 's';aa[57] = 'C';aa[58] = 'W';aa[59] = 'C';
	aa[60] = 'F';aa[61] = 'F';aa[62] = 'F';aa[63] = 'F';

	aaC.emplace('A', 0);
	aaC.emplace('C', 0);
	aaC.emplace('D', 0);
	aaC.emplace('E', 0);
	aaC.emplace('F', 0);
	aaC.emplace('G', 0);
	aaC.emplace('H', 0);
	aaC.emplace('I', 0);
	aaC.emplace('K', 0);
	aaC.emplace('L', 0);
	aaC.emplace('M', 0);
	aaC.emplace('N', 0);
	aaC.emplace('P', 0);
	aaC.emplace('Q', 0);
	aaC.emplace('R', 0);
	aaC.emplace('S', 0);
	aaC.emplace('T', 0);
	aaC.emplace('V', 0);
	aaC.emplace('W', 0);
	aaC.emplace('Y', 0);
	aaC.emplace('s', 0);
	aaC.emplace('x', 0);
}

int Analyzing::get_seq_num(const char* seq, int size)
{
	int loc = 0;
	int offset;
	for (int i = 0; i < size; i++)
	{
		offset = 1;
		for (int j = i + 1; j < size; j++)
			offset *= 4;
		if (seq[i] == 'C')
			loc += offset;
		else if (seq[i] == 'G')
			loc += offset * 2;
		else if (seq[i] == 'T')
			loc += offset * 3;
		//cout<<seq<<": "<<i<<", "<<offset<<", "<<loc<<"\n";
	}
	return loc;
}

/*------------------------------------------Results-------------------------------------------------*/

void Analyzing::resultPreparation(std::string action)
{
	if (action == "len")
		res.sequenceLength = seqLength;
	else if (action == "cdnpos")
		res.codonPosition = codonPos;
	else if (action == "cdnocc")
		res.codonOccurence = cdnOcc;
	else if (action == "gc")
		res.gcContentConcentration = concentration;
	else if (action == "cpg")
		res.cpgConcentration = concentration;
	else if (action == "aa")
		res.aminoAcidCount = aaContent;
}

/*
* {STRING TO CONST CHAR}
*	std::string str;
	char * writable = new char[str.size() + 1];
	std::copy(str.begin(), str.end(), writable);
	writable[str.size()] = '\0'; // don't forget the terminating 0

	// don't forget to free the string after finished using it
	delete[] writable;
*/