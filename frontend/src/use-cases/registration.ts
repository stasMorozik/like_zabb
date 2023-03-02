import { Observable } from "rxjs";
import { Either, left, right } from "@sweet-monads/either";
import Ajv, {JSONSchemaType} from "ajv";
import addFormats from "ajv-formats";
import { Domain } from "../common/errors/domain";
import { Infrastructure } from "../common/errors/infrastructure";

export namespace Registration {

  // export namespace Adapters {
  //   export class Api implements Ports.Api {
  //     fetch(dto: Dto) {
  //       throw new Error("Method not implemented.");
  //     }
  //   }

  //   export class Emiter implements Ports.Emiter {
  //     emit(either: Either<Domain.Error | Infrastructure.Error, Success>): void {
  //       throw new Error("Method not implemented.");
  //     }
  //   }
  // }

  export namespace Ports {
    export interface Api {
      fetch(dto: Dto): Observable<Either<Infrastructure.Error, Success>>
    }

    export interface Emiter {
      emit(either: Either<Domain.Error | Infrastructure.Error, Success>): void
    }
  }

  export type Dto = {
    name: string
    email: string
    password: string
    confirmPassword: string
  }

  export class Success {
    constructor(
      readonly message: string
    ){}
  }

  export class Validator {
    private readonly _validate: any;
    private readonly _ajv: Ajv;

    constructor(
    ){
      this._ajv = new Ajv();
      addFormats(this._ajv, ["email", "password"]);
      this._validate = this._ajv.compile(this.schema);
    }

    private readonly schema: JSONSchemaType<Dto> = {
      type: "object",
      properties: {
        name: {type: "string"},
        email: {
          type: "string",
          format: 'email'
        },
        password: {
          type: "string",
          format: "password"
        },
        confirmPassword: {
          type: "string",
          format: "password"
        }
      },
      required: ["name", "email", "password", "confirmPassword"],
      additionalProperties: false
    }

    valid(dto: Dto): Either<Domain.Error, Success> {
      if (!this._validate(dto)) {
        return left(new Domain.Error('Failed'));
      }

      return right(new Success('Successfully'));
    }
  }

  export class UseCase {
    constructor(
      readonly _validator: Validator,
      readonly _api: Ports.Api,
      readonly _emiter: Ports.Emiter
    ){}

    registry(dto: Dto) {
      const maybeValid = this._validator.valid(dto);
      if (maybeValid.isLeft()) {
        this._emiter.emit(maybeValid);
      }

      if (maybeValid.isRight()) {
        this._api.fetch(dto).subscribe((maybeRight: Either<Infrastructure.Error, Success>) => {
          this._emiter.emit(maybeRight);
        });
      }
    }
  }
}
