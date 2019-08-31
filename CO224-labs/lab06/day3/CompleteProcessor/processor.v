module main;
	reg RESET;
	wire [31:0] instruction;
	reg CLK;
	wire BUSY;
	
	wire aSelect, bSelect, iSelect, regWriteEn;
	wire [2:0] INaddr, OUT1addr, OUT2addr;
	wire [2:0] aluSelect;
	wire [7:0] ImmediateValue;
	
	wire [7:0] aluResult, out1, out2, out1Invert, out2Invert, muxAOut, muxBOut, muxIOut;
	
	wire [7:0] PC;
	
	wire readEn,writeEn;
	wire [7:0] dataAddress,dataOut;
	wire [7:0] regIn;
	
	//controlUnit(CLK, BUSY, INST, INaddr, OUT1addr, OUT2addr, ImmediateValue, aSelect, bSelect, iSelect, aluSelect, regWriteEn);
	controlUnit myControl(CLK,BUSY, instruction, INaddr, OUT1addr, OUT2addr, ImmediateValue, aSelect, bSelect, iSelect, aluSelect, regWriteEn, PC, writeEn,readEn,dataAddress,wSelect);
	
	// ALU(aluResult, data1,data2,aluSelect);
	ALU myALU(aluResult, muxIOut, muxBOut, aluSelect);
	
	// regFile(IN,OUT1,OUT2,INaddr,OUT1addr,OUT2addr,CLK,RESET,WRITE_EN);
	regFile myRegFile(regIn, out1, out2, INaddr, OUT1addr, OUT2addr, CLK, RESET, regWriteEn);
	
	// 2's complement units (Out, In)
	compTwo compA(out1Invert, out1);
	compTwo compB(out2Invert, out2);
	
	// mux for data paths (Out, 0-input, 1-input)
	mux muxA(muxAOut, out1 ,out1Invert, aSelect);
	mux muxB(muxBOut, out2, out2Invert, bSelect);
	mux muxI(muxIOut, muxAOut, ImmediateValue, iSelect);
	
	// instruction register (inst, instAddr, CLK) 
	instCache myInstCache(instruction, BUSY, PC, CLK);
	
	// program counter (PC, CLK, RESET, BUSY)
	programCounter myPC(PC, CLK, RESET, BUSY);
	
	// data memory (CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY)
	dataCache myCache(CLK, RESET, readEn,writeEn ,dataAddress , dataOut, aluResult, BUSY);
	
	// new mux for  ( aluResult | dataOut )---> regIn (control signal = wSelect)
	mux muxW(regIn,aluResult,dataOut,wSelect);
	
	initial begin

		#0
		$dumpfile("out.vcd"); 
		$dumpvars(0, myControl);
		$dumpvars(0, myALU);
		$dumpvars(0, myRegFile);
		$dumpvars(0, myPC);
		$dumpvars(0, myCache);
		$dumpvars(0, myInstCache);

		$dumpvars(0, compA);
		$dumpvars(0, muxA);
		$dumpvars(0, compB);
		$dumpvars(0, muxB);
		$dumpvars(0, muxI);
				
		RESET <= 0;
		CLK <= 0;
		//BUSY <= 0;
		
		#5000
		$finish;
		
	end
	
	always begin
		#5 CLK = ~CLK;
    end
		
endmodule

// Control Unit
module controlUnit(CLK, BUSY, INST, INaddr, OUT1addr, OUT2addr, ImmediateValue, aSelect, bSelect, iSelect, aluSelect, regWriteEn, PC, writeEn,readEn,dataAddress,wSelect);
	
	output [7:0] dataAddress;
	output writeEn, readEn, wSelect;
	
	input CLK,BUSY;
	input [31:0] INST;
	
	input [7:0] PC;
	
	output [2:0] OUT1addr, OUT2addr, INaddr;
	output [7:0] ImmediateValue;
	
	output aSelect, bSelect, iSelect, regWriteEn;
	output [2:0] aluSelect;

	reg [31:0] IR;					// instruction register
	
	reg [7:0] opcode;
	reg [7:0] destination;
	reg [7:0] source2;
	reg [7:0] source1;
	reg [9:0] currentControlCode;
	reg [9:0] EEPROM[7:0];
		
	initial begin					
		// regWrite_aluSelect(3)_dataOneIm(1)_aSelect(1)_bSelect(1)_iSelect(1)_NC 
		EEPROM[0] <=  9'b1_000_1_0_0_1_0;		// loadi
		EEPROM[1] <=  9'b1_000_0_0_0_0_0;		// mov
		EEPROM[2] <=  9'b1_001_0_0_0_0_0;		// add
		EEPROM[3] <=  9'b1_001_0_0_1_0_0;		// sub
		EEPROM[4] <=  9'b1_010_0_0_0_0_0;		// and
		EEPROM[5] <=  9'b1_011_0_0_0_0_0;		// or
		EEPROM[6] <=  9'b1_000_0_0_0_0_0;		// load
		EEPROM[7] <=  9'b0_000_0_0_0_0_0;		// store
	end

	assign readEn=(opcode==6)? 1'b1 : 1'b0;
	assign writeEn=(opcode==7)? 1'b1 : 1'b0;
	assign wSelect=(opcode==6)? 1'b1 : 1'b0;
	
	// ALU operation selector
	assign aluSelect = currentControlCode[7:5];
	
	// Address selectors for register
	assign OUT1addr = source1[2:0];
	assign OUT2addr = source2[2:0];
	assign INaddr = destination[2:0];
	
	assign regWriteEn = currentControlCode[8];
	
	assign ImmediateValue = source1;
	assign dataAddress = (opcode==6) ? source1 : destination;
	
	// Control Signals for MUX modules
	assign aSelect = currentControlCode[3];
	assign bSelect = currentControlCode[2];
	assign iSelect = currentControlCode[1];
	
	 always @(PC) begin
		IR <= INST;
		opcode <= IR[31:24];	
		destination <= IR[23:16];	
		source2 <= IR[15:8];	
		source1 <= IR[7:0];	
		currentControlCode = EEPROM[opcode][11:0];
		$display("\nPC: %d | Inst: %d | %d | %d | %d", PC, opcode, destination, source2, source1);
	end 	
endmodule

// ALU Unit
module ALU(aluResult, data1,data2,aluSelect);

	input [7:0] data1,data2;
	input [2:0] aluSelect;
	output [7:0] aluResult;
	
    reg [7:0] aluResult;
   
	always @ (data1 or data2 or aluSelect) begin
    	case (aluSelect)
    		3'b000: aluResult = data1;
    		3'b001: aluResult = data1+data2;
    		3'b010: aluResult = data1&data2;
    		3'b011: aluResult = data1|data2;
    	endcase
	end	
endmodule

// 8x2 Mux
module mux(OUT,A,B,S);

	input [7:0] A,B;
	input S;
	output [7:0] OUT;
		
	assign OUT=(S==0)? A:B;
endmodule

// 8bit 2's Comp
module compTwo(OUT,IN);
	input [7:0] IN;
	output [7:0] OUT;
	assign OUT=(~IN+1);
endmodule
	
// 8x8 Register file
module regFile(IN,OUT1,OUT2,INaddr,OUT1addr,OUT2addr,CLK,RESET,WRITE_EN);

	input CLK,RESET,WRITE_EN, BUSY;
	input [2:0] INaddr,OUT1addr,OUT2addr;
	input [7:0] IN;
	output reg [7:0] OUT1,OUT2;

	reg [7:0] registers[7:0];
    
	integer i;
	
	always @(posedge CLK) begin
		OUT1 <= registers[OUT1addr];
		OUT2 <= registers[OUT2addr];
		//$display("\tReg-> %d [%d] and %d [%d]", OUT1, OUT1addr, OUT2, OUT2addr); 
		
		//for (i=0;i<8; i=i+1)
		//	$display ("register[ %d ] = %b", i, registers[i]);
	end
	
	always @(negedge CLK) begin
		if(WRITE_EN)  begin
			registers[INaddr]<=IN;
			//$display("\n\t-> Reg the value %d into r%d", IN, INaddr); 
			
			//for (i=0;i<8; i=i+1)
			//		$display ("register[ %d ] = %b", i, registers[i]);
					
		end
	end
	
	// Reset the register values
	always @(posedge RESET) begin   
        $display("Register reset"); 
        for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
	end

	// Begin with 0 values
	initial begin
        for(i=0;i<8;i=i+1) begin
    	    registers[i] <= 8'b00000000;
    	end
	end
endmodule	

// Program Counter
module programCounter(PC, CLK, RESET, BUSY);
	
	input CLK, RESET, BUSY;
	output [7:0] PC;    
	
    reg [7:0] PC;
    
    always @(posedge CLK) begin
		// PC increases on negative edge
		if (BUSY==0) begin
			PC <= (PC + 8'b00000001);
		end
	end
	
	always @(posedge RESET) begin
		// reseting the PC value to zero
		PC <= 0;
	end
	
	initial begin
		// inisital value for PC is zero 
	    PC <= 0;
	end	
endmodule

// Data Cache
module dataCache(
	CLK,RESET,
	readEn,writeEn,		// read and write enable data bits 
	address,			// read/write address
	dataIn,				// write the data to cache from processor
	dataOut,			// send data to the processor
	busy);				// wait flag

	//$dumpvars(0, V, D);

	output busy;					// BUSY signal to the processor
	wire busyMem;					// busy state of MainMemory
	reg busyCache;					// busy state of DataCache

	or(busy, busyMem, busyCache);

	input       CLK, RESET;	
	input		readEn,writeEn;		// cache read/write control signals
	input[7:0]  address;			// address selector
	input[7:0]	dataIn;				// data input bus
	output[7:0] dataOut;			// data output bus

	reg[7:0] dataOut;				// processor <---- cache
	
	// -- Cache <-> DataMemory  ----------------------------------------------

	wire [15:0] dataFromM;			// cache <-------- data memory 
	reg [15:0] dataToM;				// data memory <-- cache 
	
	reg writeM, readM;				// data memory read/write control signals
	reg[6:0] dataMAddress;			// data block address (7bits)

	// data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);
	data_mem myMem(CLK, RESET, readM, writeM, dataMAddress, dataFromM, dataToM, busyMem);
	

	// -- Cache special variables ---------------------------------------------
	reg [3:0] TAG [7:0];			// 8 tags of 4bits
	reg V [7:0];					// 8 valid bits
	reg D [7:0];					// 8 dirty bits
	
	reg [15:0] cacheData[7:0];		// 8 blocks of 2 byte data 
	
	wire offset;
	wire[3:0] tag;
	wire[2:0] index;
	
	integer i;	

	assign offset = address[0];
	assign tag = address[7:4];
	assign index = address[3:1];

	// Reset the cache data on RST = 1 
	always @(posedge RESET) begin
		for (i=0;i<8; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 4'bxxxx;
			V[i] <= 1'b0;
			D[i] <= 1'b0;
		end
		busyCache <= 1'b0;
		//$display("\tRESET\n");
	end
	
	initial begin
		for (i=0;i<8; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 1'b0;
			V[i] <= 1'b0;
			D[i] <= 1'b0;
			
		end
		busyCache <= 1'b0;
		writeM <=0;
		readM <=0;
		dataMAddress <=0;
		dataToM <=0;

		//$display("\tRESET\n");
	end
	
	always @(address) begin	

		// --- READ ----------
		if(!writeEn && readEn && !busyCache) begin				
				busyCache <= 1'b1;
			
				if (TAG[index]==tag && V[index]==1'b1) begin
					// tag match and valid ---> HIT, 			no need any process
					$display("\t HIT on the [ %b | %b | %b ] V[%b] D[%b] \t <tag match, valid, r>", tag, index, offset, V[index], D[index]);
					
				end else begin
					// tag isn't match --> Conflict MISS
					
					if (D[index]==1) begin
						// Write back the current block into the data memory
						$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, dirty, r>", tag, index, offset, V[index], D[index]);
				
						
						// --- Write-----------------

						writeM <= 1;
						readM <= 0;

						#10
						dataMAddress <= address[7:1];

						#10
						dataToM <= cacheData[index];
						
						#2
						writeM <=0;
						readM <=0;
						
						// ---------------------------
						
						D[index] <= 1'b0;
						
					end else begin
						$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, r>", tag, index, offset, V[index], D[index]);
					end
					
					// --- Read -----------------
					//cacheData[index][7:0] <= memory[address[7:1] + 8'b00000000 ];
					//cacheData[index][15:8] <= memory[address[7:1] + 8'b00000001]; 
					#1000

					writeM <=0;
					readM <=1;

					#10
					dataMAddress <= address[7:1];
					
					#10
					cacheData[index] <= dataFromM;

					#2 
					writeM <=0;
					readM <=0;

					// ---------------------------
					
					TAG[index] <= tag;	
					V[index] <= 1'b1;
				
				end
				
				#10
				// -- Send the required data to the processor ----
				if (offset == 0) begin
					dataOut <= cacheData[index][7:0];
				end else begin
					dataOut <= cacheData[index][15:8];
				end

				// ------------------------------------------------------------
		
				#10
				$display("Read %d from  %d", dataOut, index);
				busyCache <= 1'b0;
		end
		
		// ----- WRITE --------
		if (writeEn && !readEn && !busyCache) begin
				busyCache <= 1'b1;
				
				if (TAG[index]==tag && V[index]==1) begin
					// tag match and valid ---> HIT, 			just write
					#10
					$display("\t HIT on the [ %b | %b | %b ] V[%b] D[%b] \t <tag match, valid, w>", tag, index, address[0],  V[index], D[index]);
					D[index] <= 1'b1;
			
				end else begin
					// tag not match ---> MISS			
					
					if (D[index]==1) begin
						// Write back the current block into the data memory
						$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, w>", tag, index,  address[0], V[index], D[index]);

						// --- Write  Back ------
							
						writeM <=1;
						readM <=0;
						
						#10
						dataMAddress <= address[7:1];
						dataToM <= cacheData[index];
						
						#10				
						writeM <=0;
						readM <=0;
						
						// ---------------------------
						
					end else begin
						$display("\t MISS on the [ %b | %b | %b ] V[%b] D[%b] \t <tag not match, w>", tag, index,  address[0], V[index], D[index]);
					end
				end
				
				// write the data into cache and make dirty bit=1, valid bit=1
				#10
				cacheData[index] <= dataIn;
				TAG[index] <= tag;
				D[index] <= 1'b1;
				V[index] <= 1'b1;
				
				#10
				$display("Written %d into  %d", dataIn, index);
				busyCache <= 1'b0;
		end

		$display("");
	end
endmodule

// Data Memory
module data_mem(CLK,RESET, readEn, writeEn, dataAddress,  dataOut, dataIn , BUSY);

	input           CLK, RESET, readEn, writeEn;
	input [6:0] 	dataAddress;
	input [15:0] 	dataIn;
	output [15:0] 	dataOut;
	output			BUSY;

	reg[15:0]     	dataOut;
	reg BUSY;
	
	integer  i;

	reg [7:0] memory[255:0];

	// reset the memory values @ positive edge of the RESET signal
	always @(posedge RESET) begin
			BUSY<= 0;
			for (i=0;i<256; i=i+1)
				memory[i] <= 0;
	end

	always @(CLK) begin
			// Reading data from the memory
			if(!writeEn && readEn && !BUSY) begin
				BUSY <= 1;
				#500;  // Clock * Cycles
				dataOut <= (memory[dataAddress*2 + 0 ]*256) + memory[dataAddress*2 + 1];
				$display ("MEM: Read %d from  %d", dataOut, dataAddress);
				BUSY <= 0;
				
				//for (i=0;i<16; i=i+1)
				//	$display ("memory[ %d ] = %b", i, memory[i]);
					
			end

			if (writeEn && !readEn && !BUSY) begin
				BUSY <= 1;
				#500;  // Clock * Cycles
				memory[dataAddress*2 + 0 ] <= dataIn[15:8];
				memory[dataAddress*2 + 1 ] <= dataIn[7:0];
				
				$display ("MEM: Written %d into  %d", dataIn, dataAddress);
				BUSY <= 0;
				
				//for (i=0;i<16; i=i+1)
				//	$display ("memory[ %d ] = %b", i, memory[i]);
					
			end
	end
	
	initial begin
	    // Initialize the memory
       	BUSY<= 0;
		for (i=0;i<256; i=i+1)
			memory[i] <= 0;
	end
endmodule

//Instruction Cache
module instCache(instCurrent,busy, PC, CLK);
	
	output busy;
	wire busyInst;							// Busy signal from the instruction register
	reg busyCache;							// Busy signal from the cache register
	
	or(busy, busyInst, busyCache);

	input CLK;
	input[7:0] PC;
	
	
	output reg [31:0] instCurrent; 			// Which should return to processor
	
	input [127:0] dataFromInst; 			// 4 word block taking from instruction memory
	output reg readInst;					// signal to instruction memory say, read and give me the data 

	output reg [5:0] instAddress;
	instMem myInst(dataFromInst, busyInst, readInst, instAddress, CLK);

	// Cache special variables
	reg [3:0] TAG [0:3];					// 4 tags of 4-bit
	reg V [0:3];							// 4 valid bits
	//output reg busy;
	
	reg [127:0] cacheData[0:3];				// this contains 4 blocks of (32 bit word x 4)  
	
	wire[3:0] tag;							// 4 bit tag
	wire[1:0] index;						// 2 bit index
	wire[1:0] offset;						// 2 bit offset

	integer i;	

	assign offset = PC[1:0];				// assign the offset from the given address
	assign tag = PC[7:4];					// assign the tag from the given address
	assign index = PC[3:2];					// assign the index from the given address

	initial begin							// initially, reset all the values to zero
		for (i=0;i<4; i=i+1) begin
			cacheData[i] <= 0;
			TAG[i] <= 4'bxxxx;
			V[i] <= 1'b0;
		end
		busyCache <= 1'b0;
		readInst <= 1'b0;

		//$display("\tRESET\n");

	end
	
	always @(PC) begin				// on each clock cycle

		if (TAG[index]==tag && V[index]==1'b1) begin
			// tag match and valid ---> HIT, 			no need any process
			$display("\t HIT  on the [ %b | %b | %b ] V[%b]", tag, index, offset, V[index]);

		end else begin
			// tag isn't match --> Conflict MISS
			$display("\t MISS on the [ %b | %b | %b ] V[%b]\t <loading from the instruction memory>", tag, index, offset, V[index]);

			busyCache <= 1'b1;

			// --- Load -----------------

			readInst <= 1'b1;			// Ask to read
			instAddress <= PC[7:2];		// Data Block address (6 bits)

			#10
			cacheData[index] <= dataFromInst;		// Assign receiver 128bit data to corresponding cache block
			
			// ---------------------------
			
			#2
			readInst <= 1'b0;
			TAG[index] <= tag;	
			V[index] <= 1'b1;	

			busyCache <= 1'b0;

		end


		// Output the requesed data
		if (offset == 0) begin
			instCurrent <= cacheData[index][31:0];
		end else if (offset == 2) begin
			instCurrent <= cacheData[index][63:32];
		end else if (offset == 3) begin
			instCurrent <= cacheData[index][95:64];
		end else begin
			instCurrent <= cacheData[index][127:96];
		end

		//$display("Sending the instruction %b into processor", instCurrent);
		busyCache <= 1'b0;

	end

	//always begin
	//	#10 $display("\t Tags: %b %b %b %b | valid %b %b %b %b\n", TAG[2'b00], TAG[2'b01], TAG[2'b10], TAG[2'b11], V[0], V[1], V[2], V[3]);
	//end
endmodule

// Instruction Memory
module instMem(instCurrent, busy, read, instAddr, CLK);

    input CLK;						// clock
    input read;						// read and send the data to instruction cache if this is equal to 1
    input [5:0] instAddr;			// the block address [bits:6][offset:2]

    output reg busy;				// send the busy signal to cache when reading the data
    output reg [127:0] instCurrent;	// current data buffer of 4 instructions [bits:128]
    
    reg [255:0] memory [31:0];		// 256 x [4byte] register file
	
    initial begin
    	busy <= 0;

		//	Format	:			  |  OPCODE |  Dest  |  Source2  |  Source1  |
		
		// empty
		memory[0] <= 32'b00000000_00000000_xxxxxxxx_00000000;
		
		//loadi r0,x,#32;
		memory[1] <= 32'b00000000_00000000_00000000_00100000;
		
		// loadi r1,x,#8;
		memory[2] <= 32'b00000000_00000001_00000000_00001000;
		
		// str #2,x,r0;
		memory[3] <= 32'b00000111_00000010_00000000_00000000;
		
		//str #6,x,r1;	
		memory[4] <= 32'b00000111_00000110_00000000_00000001;	

		//load r2,x,#2;
		memory[5]  <= 32'b00000110_00000010_00000000_00000010;
		
		//load r3,x,#6;
		memory[6]  <= 32'b00000110_00000011_00000000_00000110;

		//load r5,x,#4;
		memory[7]  <= 32'b00000110_00000101_00000000_00000100;	
	
    end

	always @(CLK) begin
		if(read == 1'b1) begin
			busy <= 1'b1;

			instCurrent[31:0] <= memory[instAddr];
			instCurrent[63:32] <= memory[instAddr+1];
			instCurrent[95:64] <= memory[instAddr+2];
			instCurrent[127:96] <= memory[instAddr+3];

			$display("\tSent the block %d to the Instruction Cache", instAddr);
			#1000 busy <= 1'b0;
		end
	end
endmodule
